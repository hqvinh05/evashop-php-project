<?php
session_start();
session_destroy(); // Hủy session

// XÓA COOKIE
if (isset($_COOKIE['user_login'])) {
    setcookie('user_login', '', time() - 3600, "/"); 
}

// SỬA: Chuyển hướng về trang login mới (cùng thư mục auth)
header("Location: EVA_auth_login.php");
exit();
?>