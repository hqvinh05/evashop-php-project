<?php
session_start();
include('../includes/EVA_system_db.php');
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập!']); exit;
}

$u = $_SESSION['username'];
$today = date('Y-m-d');
$cost = 500; // Giá gốc
$is_free = false;

// 1. Kiểm tra User & Lượt Free
$user = $conn->query("SELECT diem_tich_luy, ngay_quay_free FROM taikhoan WHERE username='$u'")->fetch_assoc();

// Nếu ngày lưu trong DB khác hôm nay => ĐƯỢC FREE
if ($user['ngay_quay_free'] != $today) {
    $cost = 0;
    $is_free = true;
} else {
    // Nếu hết lượt free thì check tiền
    if ($user['diem_tich_luy'] < $cost) {
        echo json_encode(['status' => 'error', 'message' => 'Hết lượt miễn phí! Bạn cần 500 Xu để quay tiếp.']); exit;
    }
}

// 2. Cấu hình giải thưởng (Giữ nguyên)
$prizes = [
    0 => ['name' => 'Chúc may mắn',  'code' => '',       'val' => 0,     'percent' => 50],
    1 => ['name' => 'Voucher 10k',   'code' => 'VC10K',  'val' => 10000, 'percent' => 30],
    2 => ['name' => 'Voucher 20k',   'code' => 'VC20K',  'val' => 20000, 'percent' => 15],
    3 => ['name' => 'Voucher 50k',   'code' => 'VC50K',  'val' => 50000, 'percent' => 4],
    4 => ['name' => 'Voucher 100k',  'code' => 'VC100K', 'val' => 100000,'percent' => 1]
];

// 3. Random
$rand = rand(1, 100);
$current_percent = 0;
$result_index = 0;
foreach ($prizes as $index => $prize) {
    $current_percent += $prize['percent'];
    if ($rand <= $current_percent) { $result_index = $index; break; }
}
$won_prize = $prizes[$result_index];

// 4. Xử lý Database
$conn->begin_transaction();
try {
    // a. Trừ tiền (Nếu không free)
    if (!$is_free) {
        $conn->query("UPDATE taikhoan SET diem_tich_luy = diem_tich_luy - $cost WHERE username='$u'");
    }
    
    // b. Cập nhật ngày quay free (Nếu đang dùng free)
    if ($is_free) {
        $conn->query("UPDATE taikhoan SET ngay_quay_free = '$today' WHERE username='$u'");
    }

    // c. Lưu voucher
    if ($won_prize['val'] > 0) {
        $code_unique = $won_prize['code'] . rand(1000, 9999);
        $conn->query("INSERT INTO kho_voucher (username, ten_voucher, ma_code, gia_tri) VALUES ('$u', '{$won_prize['name']}', '$code_unique', '{$won_prize['val']}')");
    }
    
    // Lấy lại số dư & Trạng thái free tiếp theo
    $new_data = $conn->query("SELECT diem_tich_luy FROM taikhoan WHERE username='$u'")->fetch_assoc();
    
    $conn->commit();
    
    echo json_encode([
        'status' => 'success',
        'prize_index' => $result_index,
        'prize_name' => $won_prize['name'],
        'new_balance' => $new_data['diem_tich_luy'],
        'is_free_used' => true // Báo cho JS biết là vừa xài mất lượt free rồi
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>