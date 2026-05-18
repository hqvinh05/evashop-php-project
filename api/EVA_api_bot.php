<?php
// Bật session để làm "trí nhớ ngắn hạn" cho Bot
session_start();
header('Content-Type: application/json');

// Nhận tin nhắn từ JavaScript gửi lên
$data = json_decode(file_get_contents('php://input'), true);
$user_message = isset($data['message']) ? trim($data['message']) : '';

if (empty($user_message)) {
    echo json_encode(['reply' => 'Vui lòng nhập tin nhắn.']);
    exit;
}

// DÁN API KEY CỦA BẠN VÀO BIẾN DƯỚI ĐÂY
$gemini_api_key = 'AIzaSyB3QNmQFcNX4PM6J7scuHTNLYfE4gvQwVY';

// =========================================================================
// [BẢN VÁ LỖI DANH TÍNH] LẤY TÊN NGƯỜI ĐĂNG NHẬP ĐỂ MỚM CHO AI
// =========================================================================
// Sửa $_SESSION['user_name'] thành đúng tên biến session lúc đăng nhập của bạn
$customer_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'bạn'; 

// Kiểm tra xem có người mới đăng nhập không. Nếu có -> Xóa trí nhớ cũ (Tẩy não bot)
if (!isset($_SESSION['current_chat_user']) || $_SESSION['current_chat_user'] !== $customer_name) {
    $_SESSION['chat_history'] = []; // Xóa sạch lịch sử chat của người cũ
    $_SESSION['current_chat_user'] = $customer_name; // Ghi nhận người mới
}

// =========================================================================
// [BẢN VÁ LỖI DANH TÍNH] LẤY TÊN NGƯỜI ĐĂNG NHẬP
// =========================================================================
$customer_name = isset($_SESSION['hoten']) ? $_SESSION['hoten'] : 'bạn'; 

if (!isset($_SESSION['current_chat_user']) || $_SESSION['current_chat_user'] !== $customer_name) {
    $_SESSION['chat_history'] = []; 
    $_SESSION['current_chat_user'] = $customer_name; 
}

// =========================================================================
// [MỚI] KHO HÀNG MINI (DỮ LIỆU ĐỂ BƠM CHO AI)
// Ghi chú: Sau này giỏi PHP hơn, bạn có thể viết lệnh SQL SELECT từ Database 
// và dùng vòng lặp while() để nhét dữ liệu thật vào biến $kho_hang này.
// =========================================================================
$kho_hang = "
[DANH SÁCH SẢN PHẨM HIỆN CÓ TẠI EVASHOP]
1. Vợt công giá rẻ: Vợt Mizuno JPX 8.2 (Giá: 1.820.000đ) | Link: /EVASHOP/product/EVA_product_detail.php?id=71
2. Vợt bán chạy nhất: Vợt cầu lông Yonex Astrox 99 Pro 2025 (Giá: 4.959.000đ) | Link: /EVASHOP/product/EVA_product_detail.php?id=11
3. Vợt thủ tốt nhất: Vợt Lining Halbertec 8000 (Giá: 2.800.000đ) | Link: /EVASHOP/product/detail.php?id=3
4. Dây cước cầu lông: Dây Cước Victor VBS-61 (Giá: 5.000đ) | Link: /EVASHOP/product/EVA_product_detail.php?id=168
";

// =========================================================================
// [NÂNG CẤP PROMPT v3.0] ÉP BOT DÙNG DỮ LIỆU TỪ KHO HÀNG VÀ CHÈN LINK HTML
// =========================================================================
$system_instruction = "Bạn là nhân viên tư vấn nhiệt tình của EVASHOP. Khách đang chat tên là: $customer_name.

NGUỒN DỮ LIỆU SẢN PHẨM (BẮT BUỘC DÙNG):
$kho_hang

LUẬT GIAO TIẾP & TƯ VẤN (TUÂN THỦ TUYỆT ĐỐI):
1. Chỉ gọi tên '$customer_name' ở câu chào đầu tiên. Các câu sau xưng 'mình' và gọi là 'bạn'.
2. KHI KHÁCH HỎI TƯ VẤN SẢN PHẨM: BẮT BUỘC phải trích xuất ít nhất 1-2 sản phẩm từ NGUỒN DỮ LIỆU SẢN PHẨM ở trên để giới thiệu. TUYỆT ĐỐI KHÔNG được nói chung chung. TUYỆT ĐỐI KHÔNG tự bịa ra sản phẩm hoặc link không có trong NGUỒN DỮ LIỆU.
3. CÁCH TRÌNH BÀY LINK: Khi giới thiệu sản phẩm, BẮT BUỘC phải chèn link bằng cú pháp thẻ HTML. Ví dụ: Vợt Victor Thruster K 15 (1.200.000đ) - <a href='ĐIỀN_LINK_VÀO_ĐÂY' target='_blank' style='color:#007bff; text-decoration:underline;'>Xem chi tiết tại đây</a>.
4. Luôn kết thúc bằng một câu hỏi gợi mở để chốt sale (Ví dụ: Bạn thấy mẫu này có hợp với lối đánh của mình không ạ?).";

// Khởi tạo bộ nhớ nếu chưa có
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

// Ghi nhớ câu hỏi của khách
$_SESSION['chat_history'][] = [
    "role" => "user",
    "parts" => [ ["text" => $user_message] ]
];

// Cắt đuôi lịch sử: Giữ lại 10 tin nhắn gần nhất để chống quá tải
if (count($_SESSION['chat_history']) > 10) {
    $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -10);
}

// Chuẩn bị URL và dữ liệu gửi sang máy chủ Google
$api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $gemini_api_key;
$payload = [
    "system_instruction" => [
        "parts" => [ ["text" => $system_instruction] ]
    ],
    "contents" => $_SESSION['chat_history'],
    "generationConfig" => [
        "temperature" => 0.6, 
        "maxOutputTokens" => 800 // ĐÃ TĂNG DUNG LƯỢNG LÊN 800 ĐỂ KHÔNG BỊ HỤT HƠI
    ]
];

// Dùng cURL gọi API
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

// TẮT CHẶN BẢO MẬT SSL CỦA XAMPP
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch); 
curl_close($ch);

// Xử lý kết quả Google trả về
if ($http_code == 200) {
    $result = json_decode($response, true);
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $bot_reply = $result['candidates'][0]['content']['parts'][0]['text'];
        
        // Ghi nhớ câu trả lời của Bot
        $_SESSION['chat_history'][] = [
            "role" => "model",
            "parts" => [ ["text" => $bot_reply] ]
        ];

        // Format HTML
        $bot_reply_html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $bot_reply);
        echo json_encode(['reply' => $bot_reply_html]);
    } else {
        echo json_encode(['reply' => 'Hệ thống đang bảo trì trí nhớ, bạn đợi xíu nha.']);
    }
} else {
    $error_msg = 'Lỗi kết nối máy chủ AI (Mã: ' . $http_code . ').';
    if (!empty($curl_error)) {
        $error_msg .= ' Chi tiết cURL: ' . $curl_error;
    }
    echo json_encode(['reply' => $error_msg]);
}
?>