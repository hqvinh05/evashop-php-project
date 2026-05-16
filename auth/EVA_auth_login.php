<?php
session_start();
// SỬA: Đường dẫn ra ngoài 1 cấp để lấy DB
include '../includes/EVA_system_db.php'; 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM taikhoan WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Lưu Session
        $_SESSION['username'] = $row['username'];
        $_SESSION['hoten'] = $row['hoten'];
        $_SESSION['role'] = $row['role']; 
        
        // Lưu ID để dùng cho việc đánh giá/mua hàng sau này
        $_SESSION['user_id'] = $row['id']; 

        // XỬ LÝ GHI NHỚ ĐĂNG NHẬP
        if (isset($_POST['remember'])) {
            setcookie('user_login', $username, time() + (86400 * 30), "/");
        }

        if ($row['role'] == 1) {
            $_SESSION['admin_user'] = $row['username'];
            $_SESSION['admin_name'] = $row['hoten'];
            // SỬA: Chuyển hướng vào Dashboard Admin mới
            header("Location: ../admin/EVA_admin_dashboard.php");
        } else {
            // SỬA: Chuyển hướng ra trang chủ
            header("Location: ../index.php");
        }
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - EVA SHOP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body style="background:#f0f2f5; height:100vh; display:flex; align-items:center; justify-content:center;">

    <div class="card bg-white shadow" style="width: 400px; border-radius: 15px;">
        <div class="card-header bg-black text-warning text-center fw-bold py-3 h4">EVA SHOP LOGIN</div>
        <div class="card-body p-4">
            <?php if($error): ?><div class="alert alert-danger text-center p-2 small"><?=$error?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tài khoản</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="rem">
                    <label class="form-check-label" for="rem">Ghi nhớ đăng nhập lần sau</label>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold">ĐĂNG NHẬP</button>
            </form>
            
            <div class="text-center mt-3">
                <a href="EVA_auth_register.php" class="small text-decoration-none text-muted">Đăng ký mới</a> | 
                <a href="../index.php" class="small text-decoration-none text-muted">Về trang chủ</a>
            </div>
        </div>
    </div>

</body>
</html>