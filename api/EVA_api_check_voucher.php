<?php
session_start();
include('../includes/EVA_system_db.php');
header('Content-Type: application/json');

if (!isset($_SESSION['username']) || !isset($_POST['code'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']); exit;
}

$u = $_SESSION['username'];
$code = $conn->real_escape_string($_POST['code']);

// Kiểm tra mã trong database
$sql = "SELECT * FROM kho_voucher WHERE ma_code = '$code' AND username = '$u'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $voucher = $result->fetch_assoc();
    if ($voucher['trang_thai'] == 1) {
        echo json_encode(['status' => 'error', 'message' => 'Voucher này đã được sử dụng!']);
    } else {
        echo json_encode([
            'status' => 'success', 
            'discount' => $voucher['gia_tri'], 
            'message' => 'Áp dụng thành công! Giảm ' . number_format($voucher['gia_tri']) . 'đ'
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Mã không tồn tại hoặc không phải của bạn!']);
}
?>