<?php
ob_start(); // Bắt đầu bộ đệm đầu ra (Chống lỗi header)
session_start();
error_reporting(E_ALL); // Bật báo lỗi
ini_set('display_errors', 1);

// 1. Kết nối Database (Dùng đường dẫn tuyệt đối cho chắc ăn)
$path_db = __DIR__ . '/../includes/EVA_system_db.php';
if (file_exists($path_db)) {
    include $path_db;
} else {
    die("Lỗi: Không tìm thấy file kết nối Database tại: $path_db");
}

// 2. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/EVA_auth_login.php");
    exit();
}

// 3. Xử lý Mua lại
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    
    // Lấy chi tiết đơn hàng cũ
    $sql = "SELECT sanpham_id, so_luong FROM chitietdonhang WHERE donhang_id = $order_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Nếu chưa có giỏ thì tạo mới
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Duyệt sản phẩm và cộng dồn vào giỏ
        while ($row = $result->fetch_assoc()) {
            $sp_id = $row['sanpham_id'];
            $sl_cu = intval($row['so_luong']);

            if (isset($_SESSION['cart'][$sp_id])) {
                $_SESSION['cart'][$sp_id] += $sl_cu;
            } else {
                $_SESSION['cart'][$sp_id] = $sl_cu;
            }
        }
        
        // Thành công -> Chuyển về Giỏ hàng
        header("Location: ../cart/EVA_cart_view.php");
        exit();
    } else {
        // Không tìm thấy sản phẩm trong đơn -> Về trang danh sách SP
        echo "Không tìm thấy sản phẩm nào trong đơn hàng #$order_id";
        header("Refresh: 2; url=../product/EVA_product_list.php");
        exit();
    }
}

// Nếu không có ID -> Về trang chủ
header("Location: ../index.php");
exit();
?>