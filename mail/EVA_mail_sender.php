<?php
// mail/EVA_mail_sender.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Định nghĩa đường dẫn tuyệt đối để tránh lỗi không tìm thấy file
require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

function guiMail($to, $subject, $content) {
    $mail = new PHPMailer(true);

    try {
        // --- CẤU HÌNH SERVER ---
        // $mail->SMTPDebug = 2; // Bỏ comment dòng này nếu muốn xem chi tiết lỗi kết nối (Debug)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // --- THAY ĐỔI THÔNG TIN CỦA BẠN ---
        $mail->Username   = 'vinh26132005@gmail.com'; 
        // LƯU Ý: Đây phải là MẬT KHẨU ỨNG DỤNG (16 ký tự), KHÔNG PHẢI mật khẩu đăng nhập Gmail
        $mail->Password   = '******'; 
        // ----------------------------------

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465; 
        $mail->CharSet    = 'UTF-8';

        // --- NGƯỜI GỬI & NGƯỜI NHẬN ---
        $mail->setFrom('vinh26132005@gmail.com', 'EVA SHOP Admin'); 
        $mail->addAddress($to); 

        // --- NỘI DUNG ---
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->AltBody = strip_tags($content);

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Ghi log lỗi ra màn hình để kiểm tra
        echo "<script>console.log('Mail Error: " . addslashes($mail->ErrorInfo) . "');</script>";
        return false;
    }
}
?>