<?php
session_start();
include '../includes/EVA_system_db.php';

$msg = "";
$msg_class = "";

// XỬ LÝ GỬI LIÊN HỆ
if (isset($_POST['btnContact'])) {
    $hoten = $conn->real_escape_string($_POST['hoten']);
    $email = $conn->real_escape_string($_POST['email']);
    $noidung = $conn->real_escape_string($_POST['noidung']);
    $sdt = ""; // Form hiện tại chưa có ô nhập SĐT, để trống hoặc bạn có thể thêm input name='sdt'

    if (!empty($hoten) && !empty($email) && !empty($noidung)) {
        $sql = "INSERT INTO lienhe (ho_ten, email, sdt, noi_dung, trang_thai) VALUES ('$hoten', '$email', '$sdt', '$noidung', 0)";
        
        if ($conn->query($sql) === TRUE) {
            $msg = "Gửi tin nhắn thành công! Chúng tôi sẽ phản hồi sớm.";
            $msg_class = "alert-success";
        } else {
            $msg = "Lỗi hệ thống: " . $conn->error;
            $msg_class = "alert-danger";
        }
    } else {
        $msg = "Vui lòng điền đầy đủ thông tin.";
        $msg_class = "alert-warning";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Liên hệ - EVA SHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .contact-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1626224583764-847890e058f5?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        .contact-info-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
            height: 100%;
            border-bottom: 3px solid #ffc107;
            transition: transform 0.3s;
        }
        .contact-info-box:hover { transform: translateY(-5px); }
        .contact-icon {
            font-size: 2.5rem;
            color: #ffc107;
            margin-bottom: 15px;
        }
        .form-control {
            border-radius: 5px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 5px rgba(255, 193, 7, 0.3);
        }
        .btn-send {
            background: linear-gradient(45deg, #ffc107, #ffdb4d);
            color: #000;
            font-weight: 800;
            font-size: 1.1rem;
            padding: 14px 30px;
            border: none;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-send:hover {
            background: linear-gradient(45deg, #e0a800, #ffc107);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.6);
        }
        .btn-send:active { transform: translateY(1px); }
    </style>
</head>
<body>
    <?php include '../includes/EVA_system_header.php'; ?>

    <div class="contact-header">
        <h1 class="fw-bold display-5">LIÊN HỆ VỚI CHÚNG TÔI</h1> 
        <p class="lead text-white-50 fs-6">Đội ngũ EVA SHOP luôn sẵn sàng hỗ trợ bạn 24/7</p>
    </div>

    <div class="container">
        <div class="row mb-5 g-4">
            <div class="col-md-4">
                <div class="contact-info-box">
                    <i class="fas fa-map-marker-alt contact-icon"></i>
                    <h5 class="fw-bold">ĐỊA CHỈ</h5>
                    <p class="text-muted mb-0">Số 333 Đường Thuận Giao 16,<br>P. Thuận Giao, TP. Thuận An, Bình Dương</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-box">
                    <i class="fas fa-phone-alt contact-icon"></i>
                    <h5 class="fw-bold">HOTLINE</h5>
                    <p class="text-muted mb-0">0909.123.456<br>0909.888.999</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-box">
                    <i class="fas fa-envelope contact-icon"></i>
                    <h5 class="fw-bold">EMAIL</h5>
                    <p class="text-muted mb-0">support@evashop.com<br>info@evashop.com</p>
                </div>
            </div>
        </div>

        <div class="row bg-white shadow-sm rounded overflow-hidden border mb-5">
            <div class="col-lg-6 p-5">
                <h3 class="fw-bold mb-4 text-uppercase border-bottom pb-2 d-inline-block border-warning">Gửi tin nhắn</h3>
                
                <?php if($msg != ""): ?>
                    <div class="alert <?=$msg_class?> alert-dismissible fade show" role="alert">
                        <?=$msg?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Họ và tên</label>
                        <input type="text" name="hoten" class="form-control" placeholder="Nhập tên của bạn..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Nhập email..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nội dung cần hỗ trợ</label>
                        <textarea name="noidung" class="form-control" rows="5" placeholder="Bạn cần giúp gì?" required></textarea>
                    </div>
                    
                    <button type="submit" name="btnContact" class="btn btn-send w-100 mt-3">
                        <i class="fas fa-paper-plane me-2"></i> GỬI NGAY
                    </button>
                </form>
            </div>

            <div class="col-lg-6 p-0">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.4947230467773!2d106.71790431480112!3d10.849924992271816!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752876646b9df9%3A0x62957e62a223707!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLaW5o t4bq_IC0gTHXhuq10IFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1679543849547!5m2!1svi!2s" 
                    width="100%" height="100%" style="border:0; min-height: 550px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>