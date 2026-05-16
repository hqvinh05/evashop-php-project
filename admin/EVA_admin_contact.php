<?php
session_start();
include '../includes/EVA_system_db.php';

// --- INCLUDE PHPMAILER (Để gửi mail phản hồi) ---
require '../mail/PHPMailer/PHPMailer.php';
require '../mail/PHPMailer/Exception.php';
require '../mail/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('Location: ../auth/EVA_auth_login.php'); exit();
}

// XỬ LÝ GỬI PHẢN HỒI
if (isset($_POST['btnReply'])) {
    $id_msg = intval($_POST['id_msg']);
    $email_khach = $_POST['email_khach'];
    $noi_dung_tra_loi = $_POST['reply_content'];
    
    // 1. Gửi Mail
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->Username = 'vinh26132005@gmail.com'; // Email Admin
        $mail->Password = 'djbr ttie pzlo suae';    // App Password

        $mail->setFrom($mail->Username, 'EVA SHOP Support');
        $mail->addAddress($email_khach);
        $mail->isHTML(true);
        $mail->Subject = "Phản hồi từ EVA SHOP về thắc mắc của bạn";
        $mail->Body = "
            <h3>Chào bạn,</h3>
            <p>Cảm ơn bạn đã liên hệ với EVA SHOP.</p>
            <p><strong>Nội dung phản hồi:</strong></p>
            <blockquote style='background:#f9f9f9; padding:15px; border-left:5px solid #ffc107;'>$noi_dung_tra_loi</blockquote>
            <p>Trân trọng,<br>Đội ngũ hỗ trợ EVA SHOP.</p>
        ";
        $mail->send();

        // 2. Cập nhật Database
        $stmt = $conn->prepare("UPDATE lienhe SET trang_thai=1, phan_hoi=? WHERE id=?");
        $stmt->bind_param("si", $noi_dung_tra_loi, $id_msg);
        $stmt->execute();

        echo "<script>alert('Đã gửi phản hồi thành công!'); window.location.href='EVA_admin_contact.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Lỗi gửi mail: {$mail->ErrorInfo}');</script>";
    }
}

// LẤY DANH SÁCH TIN NHẮN
$sql = "SELECT * FROM lienhe ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý liên hệ - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-envelope"></i> HỘP THƯ KHÁCH HÀNG</h2>
            <a href="../index.php" class="btn btn-outline-secondary">Về trang chủ</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Khách hàng</th>
                            <th>Nội dung</th>
                            <th>Ngày gửi</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?=$row['id']?></td>
                            <td>
                                <strong><?=$row['ho_ten']?></strong><br>
                                <small class="text-muted"><?=$row['email']?></small><br>
                                <small><?=$row['sdt']?></small>
                            </td>
                            <td>
                                <p class="mb-1 text-truncate" style="max-width: 300px;"><?=$row['noi_dung']?></p>
                                <?php if($row['phan_hoi']): ?>
                                    <small class="text-success"><i class="fas fa-reply"></i> <?=$row['phan_hoi']?></small>
                                <?php endif; ?>
                            </td>
                            <td><?=date('d/m/Y H:i', strtotime($row['ngay_gui']))?></td>
                            <td>
                                <?php if($row['trang_thai'] == 1): ?>
                                    <span class="badge bg-success">Đã trả lời</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row['trang_thai'] == 0): ?>
                                <button class="btn btn-primary btn-sm" onclick="openReplyModal(<?=$row['id']?>, '<?=$row['email']?>', '<?=addslashes($row['ho_ten'])?>')">
                                    <i class="fas fa-paper-plane"></i> Trả lời
                                </button>
                                <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>Xong</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="replyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Phản hồi khách hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_msg" id="id_msg">
                        <input type="hidden" name="email_khach" id="email_khach">
                        
                        <div class="mb-3">
                            <label class="form-label">Gửi đến:</label>
                            <input type="text" class="form-control" id="show_email" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung phản hồi:</label>
                            <textarea name="reply_content" class="form-control" rows="5" required placeholder="Nhập câu trả lời của bạn..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="btnReply" class="btn btn-primary">Gửi Mail</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openReplyModal(id, email, name) {
            document.getElementById('id_msg').value = id;
            document.getElementById('email_khach').value = email;
            document.getElementById('show_email').value = name + " (" + email + ")";
            new bootstrap.Modal(document.getElementById('replyModal')).show();
        }
    </script>
</body>
</html>