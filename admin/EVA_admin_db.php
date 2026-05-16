<?php
// Sử dụng file kết nối chung của toàn hệ thống
// __DIR__ là thư mục hiện tại (admin), đi ra ngoài 1 cấp (..) để vào includes
include_once __DIR__ . '/../includes/EVA_system_db.php';

// Kiểm tra lại biến $conn cho chắc chắn (phòng trường hợp file include bị lỗi)
if (!isset($conn)) {
    die("Lỗi: Không thể tải cấu hình kết nối CSDL từ hệ thống.");
}
?>