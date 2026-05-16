<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
// 1. KẾT NỐI CSDL (GIỮ NGUYÊN)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "evashop"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// 2. HÀM TÌM HÌNH ẢNH (Đã sửa lại: Tìm trong thư mục 'images' ở gốc)
function tim_hinh_anh($ten_file) {
    // dirname(__DIR__) lấy thư mục cha của 'includes', tức là thư mục gốc EVASHOP
    $server_path = dirname(__DIR__) . '/images/'; // Kiểm tra file trên ổ cứng
    $web_path = 'images/'; // Đường dẫn hiển thị trên web
    
    $exts = ['.webp', '.jpg', '.png', '.jpeg'];
    
    // Lấy tên gốc (bỏ đuôi cũ nếu có) để tránh lỗi trùng lặp đuôi (vd: anh.jpg.webp)
    $name = pathinfo($ten_file, PATHINFO_FILENAME);
    
    foreach ($exts as $ext) {
        if (file_exists($server_path . $name . $ext)) {
            // Nếu tìm thấy file thì trả về đường dẫn tương đối để HTML dùng
            // Lưu ý: Khi include file này, ta cần xử lý đường dẫn tương đối tùy trang
            // Nhưng tạm thời cứ trả về 'images/...' 
            return $web_path . $name . $ext;
        }
    }
    return ''; // Trả về rỗng nếu không thấy
}
?>