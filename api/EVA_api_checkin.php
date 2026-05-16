<?php
session_start();
// Đảm bảo đường dẫn này trỏ đúng file kết nối DB của bạn
include('../includes/EVA_system_db.php'); 

header('Content-Type: application/json');

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['username'])) { 
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để điểm danh']);
    exit;
}

$username = $_SESSION['username'];
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

// 2. CHECK: HÔM NAY ĐIỂM DANH CHƯA?
$sql_check = "SELECT id FROM lich_su_diem_danh WHERE username = '$username' AND ngay_diem_danh = '$today'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Hôm nay bạn đã điểm danh rồi!']);
    exit;
}

// 3. TÍNH CHUỖI NGÀY (STREAK)
$sql_yesterday = "SELECT chuoi_ngay FROM lich_su_diem_danh WHERE username = '$username' AND ngay_diem_danh = '$yesterday'";
$result_yesterday = $conn->query($sql_yesterday);

$chuoi_ngay_moi = 1; // Mặc định reset về 1

if ($result_yesterday->num_rows > 0) {
    $row = $result_yesterday->fetch_assoc();
    $chuoi_ngay_moi = $row['chuoi_ngay'] + 1;
}

// Nếu chuỗi > 7 ngày -> Reset về 1
if ($chuoi_ngay_moi > 7) {
    $chuoi_ngay_moi = 1;
}

// 4. SỐ ĐIỂM THƯỞNG
$bang_diem = [
    1 => 100,
    2 => 200,
    3 => 300,
    4 => 400,
    5 => 500,
    6 => 800,
    7 => 1500 
];
$diem_nhan = $bang_diem[$chuoi_ngay_moi];

// 5. INSERT VÀ UPDATE TIỀN
$conn->begin_transaction(); // Bắt đầu giao dịch an toàn

try {
    // a. Lưu lịch sử
    $sql_insert = "INSERT INTO lich_su_diem_danh (username, ngay_diem_danh, chuoi_ngay, diem_nhan) 
                   VALUES ('$username', '$today', '$chuoi_ngay_moi', '$diem_nhan')";
    $conn->query($sql_insert);

// b. Cộng tiền vào CẢ 2 CỘT: diem_tich_luy (để tiêu) và diem_thanh_vien (để lên cấp)
    $sql_update_balance = "UPDATE taikhoan 
                           SET diem_tich_luy = diem_tich_luy + $diem_nhan, 
                               diem_thanh_vien = diem_thanh_vien + $diem_nhan 
                           WHERE username = '$username'";
    
    $conn->query($sql_update_balance);
    
    // c. Lấy số dư mới để hiển thị
    $sql_get_new_balance = "SELECT diem_tich_luy FROM taikhoan WHERE username = '$username'";
    $res_balance = $conn->query($sql_get_new_balance);
    $new_balance = $res_balance->fetch_assoc()['diem_tich_luy'];

    $conn->commit(); // Lưu thay đổi

    echo json_encode([
        'status' => 'success', 
        'points_added' => $diem_nhan,
        'new_total_coin' => $new_balance,
        'day_streak' => $chuoi_ngay_moi, // Biến quan trọng để JS đọc
        'message' => "Điểm danh ngày $chuoi_ngay_moi thành công! +$diem_nhan điểm."
    ]);

} catch (Exception $e) {
    $conn->rollback(); // Nếu lỗi thì hoàn tác
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>