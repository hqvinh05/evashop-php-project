<?php
session_start();

// 1. Gọi file kết nối database (SỬA đường dẫn)
if (!isset($conn)) {
    if (file_exists('../includes/EVA_system_db.php')) { 
        include '../includes/EVA_system_db.php'; 
    } else {
        die("Lỗi: Không tìm thấy file kết nối database!");
    }
}

$mess = '';

// 2. Xử lý Đăng Ký
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = mysqli_real_escape_string($conn, $_POST['password']);
    $name = mysqli_real_escape_string($conn, $_POST['hoten']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // 3. Kiểm tra tên đăng nhập
    $check_sql = "SELECT * FROM taikhoan WHERE username = '$u'";
    $check = $conn->query($check_sql);

    if($check->num_rows > 0){
        $mess = "<div class='alert alert-danger fw-bold'>❌ Tên đăng nhập này đã có người dùng!</div>";
    } else {
        // 4. Thêm vào Database
        $sql = "INSERT INTO taikhoan (username, password, hoten, email, role) VALUES ('$u', '$p', '$name', '$email', 0)";
        
        if($conn->query($sql)){
            
            // --- GỬI EMAIL (SỬA đường dẫn file mail) ---
            if (file_exists('../mail/EVA_mail_sender.php')) {
                include_once '../mail/EVA_mail_sender.php';
                
                $tieu_de = "Đăng ký thành công - EVA SHOP";
                $noi_dung = "
                    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        <h2 style='color: #ffc107;'>🎉 Chào mừng đến với EVA SHOP!</h2>
                        <p>Xin chào <strong>$name</strong>,</p>
                        <p>Bạn đã đăng ký tài khoản thành công. Dưới đây là thông tin đăng nhập:</p>
                        <div style='background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                            <p><strong>Tài khoản:</strong> $u</p>
                            <p><strong>Mật khẩu:</strong> $p</p>
                        </div>
                        <p>Hãy truy cập website và đăng nhập để bắt đầu mua sắm!</p>
                        <p>Trân trọng,<br><strong>Ban quản trị EVA SHOP</strong></p>
                    </div>
                ";

                @guiMail($email, $tieu_de, $noi_dung);
            }
            // ------------------------------------------

            // SỬA: Chuyển hướng về trang login mới
            header("Location: EVA_auth_login.php");
            exit(); 
            
        } else {
            $mess = "<div class='alert alert-danger'>Lỗi hệ thống: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký thành viên</title>
    <?php include '../includes/EVA_system_header.php'; ?>
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg p-4" style="width: 500px; border-radius: 15px;">
            <div class="text-center mb-4">
                <h3 class="text-primary fw-bold text-uppercase">Đăng Ký Thành Viên</h3>
                <p class="text-muted">Tạo tài khoản để nhận ưu đãi từ EVA SHOP</p>
            </div>

            <?=$mess?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên đăng nhập (*)</label>
                    <input name="username" type="text" class="form-control" required placeholder="Ví dụ: vinh123">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mật khẩu (*)</label>
                    <input name="password" type="password" class="form-control" required placeholder="Nhập mật khẩu...">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Họ và tên</label>
                        <input name="hoten" type="text" class="form-control" required placeholder="Nguyễn Văn A">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input name="email" type="email" class="form-control" required placeholder="email@gmail.com">
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-warning btn-lg fw-bold text-dark">ĐĂNG KÝ NGAY</button>
                </div>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <span class="text-muted">Đã có tài khoản?</span>
                <a href="EVA_auth_login.php" class="text-decoration-none fw-bold ms-1">Đăng nhập tại đây</a>
            </div>
        </div>
    </div>

    <?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>