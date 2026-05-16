<?php
session_start();
// 1. KẾT NỐI DB & INCLUDE PHPMAILER
include '../includes/EVA_system_db.php';

// Gọi thư viện mail
require '../mail/PHPMailer/PHPMailer.php';
require '../mail/PHPMailer/Exception.php';
require '../mail/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cấu hình cơ bản
$base_url = "http://localhost/EVASHOP"; 
$message = "ĐẶT HÀNG THÀNH CÔNG!";
$sub_msg = "Cảm ơn bạn đã mua hàng tại EVA SHOP. Đơn hàng đang được xử lý.";
// Link ảnh mặc định (để tạm icon khác nếu lỗi)
$icon_img = "https://cdn-icons-png.flaticon.com/512/148/148767.png";
$alert_class = "alert-warning";
$id_don = 0;

// =================================================================
// HÀM GỬI MAIL CHO BANK & COD (Chuyển từ trang checkout sang)
// =================================================================
function guiMailXacNhan($emailKhach, $tenKhach, $maDonHang, $gioHang, $tongTien, $pttt) {
    $mail = new PHPMailer(true);
    global $base_url; 

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->Username = 'vinh26132005@gmail.com'; 
        $mail->Password = 'djbr ttie pzlo suae'; 

        $mail->setFrom($mail->Username, 'EVA SHOP Admin');
        $mail->addAddress($emailKhach);
        $mail->isHTML(true);
        $mail->Subject = "🔥 Xác nhận đơn hàng #$maDonHang - Cám ơn bạn đã mua sắm!";
        
        $listItems = "";
        foreach ($gioHang as $item) {
            $tenSP = $item['ten'];
            $soLuong = $item['sl'];
            $gia = number_format($item['gia'], 0, ',', '.');
            $tenFileAnh = $item['hinh']; 
            $duongDanVatLy = '../Images/' . $tenFileAnh; 
            
            $cid = 'img_' . $item['id'];
            $srcHienThi = '';

            if (file_exists($duongDanVatLy)) {
                $mail->addEmbeddedImage($duongDanVatLy, $cid);
                $srcHienThi = "cid:" . $cid;
            } else {
                $srcHienThi = "https://via.placeholder.com/60x60?text=No+Img";
            }

            $listItems .= "<tr><td style='padding:10px;text-align:center;'><img src='$srcHienThi' width='60' style='border-radius:4px;'></td><td style='padding:10px;'><strong>$tenSP</strong><br><small>SL: $soLuong</small></td><td style='padding:10px;text-align:right;'>$gia đ</td></tr>";
        }

        $linkDonHang = $base_url . "/user/EVA_user_orders.php";
        $tongTienFormat = number_format($tongTien, 0, ',', '.');

        $mail->Body = "<div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;border:1px solid #ddd;'>
            <div style='background:#ffc107;padding:20px;text-align:center;'><h2>ĐẶT HÀNG THÀNH CÔNG!</h2></div>
            <div style='padding:20px;'><p>Chào <strong>$tenKhach</strong>,</p><p>Đơn hàng <strong>#$maDonHang</strong> ($pttt) đang được xử lý.</p>
            <table style='width:100%;border-collapse:collapse;'>$listItems</table>
            <p style='text-align:right;font-size:18px;'>Tổng: <b style='color:red;'>$tongTienFormat đ</b></p>
            <div style='text-align:center;margin-top:20px;'><a href='$linkDonHang' style='background:#333;color:#fff;padding:10px 20px;text-decoration:none;'>Xem đơn hàng</a></div></div></div>";

        $mail->send();
    } catch (Exception $e) { }
}

// =================================================================
// HÀM GỬI MAIL MOMO
// =================================================================
function guiMailMoMoDep($id_don, $conn, $base_url) {
    // 1. Lấy thông tin khách hàng & đơn hàng
    $sql_info = "SELECT d.id, d.tong_tien, d.ngay_dat, t.email, t.hoten 
                 FROM donhang d JOIN taikhoan t ON d.username = t.username 
                 WHERE d.id = $id_don";
    $res_info = $conn->query($sql_info);
    
    if ($res_info->num_rows == 0) return; 
    $order = $res_info->fetch_assoc();

    // 2. Lấy danh sách sản phẩm
    $sql_items = "SELECT ct.so_luong, ct.don_gia, s.ten_sanpham, s.hinh_anh, s.id as sp_id
                  FROM chitietdonhang ct 
                  JOIN sanpham s ON ct.sanpham_id = s.id 
                  WHERE ct.donhang_id = $id_don";
    $res_items = $conn->query($sql_items);

    // 3. Cấu hình PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->Username = 'vinh26132005@gmail.com'; 
        $mail->Password = 'djbr ttie pzlo suae'; 

        $mail->setFrom($mail->Username, 'EVA SHOP Admin');
        $mail->addAddress($order['email']);
        $mail->isHTML(true);
        $mail->Subject = "✅ Thanh toán thành công đơn hàng #$id_don - EVA SHOP";

        // 4. Tạo HTML danh sách sản phẩm
        $listItemsHTML = "";
        while ($item = $res_items->fetch_assoc()) {
            $tenSP = $item['ten_sanpham'];
            $soLuong = $item['so_luong'];
            $gia = number_format($item['don_gia'], 0, ',', '.');
            
            // Xử lý ảnh
            $tenFileAnh = $item['hinh_anh']; 
            $duongDanVatLy = '../Images/' . $tenFileAnh; 
            $cid = 'img_' . $item['sp_id'];
            $srcHienThi = '';

            if (file_exists($duongDanVatLy)) {
                $mail->addEmbeddedImage($duongDanVatLy, $cid);
                $srcHienThi = "cid:" . $cid;
            } else {
                $srcHienThi = "https://via.placeholder.com/60x60?text=No+Img";
            }

            $listItemsHTML .= "
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>
                    <img src='$srcHienThi' width='60' height='60' style='object-fit:contain; border:1px solid #ddd; border-radius:4px;'>
                </td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>
                    <strong style='color:#333;'>$tenSP</strong><br>
                    <small style='color:#777;'>SL: $soLuong</small>
                </td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right; font-weight: bold;'>
                    $gia đ
                </td>
            </tr>";
        }

        $tongTienFormat = number_format($order['tong_tien'], 0, ',', '.');
        $linkDonHang = $base_url . "/user/EVA_user_orders.php";
        
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;'>
            <div style='background: #a50064; padding: 20px; text-align: center; color: #fff;'>
                <h2 style='margin: 0; text-transform: uppercase;'>THANH TOÁN MOMO THÀNH CÔNG!</h2>
            </div>
            <div style='padding: 20px;'>
                <p>Xin chào <strong>{$order['hoten']}</strong>,</p>
                <p>Hệ thống đã nhận được thanh toán cho đơn hàng <strong>#$id_don</strong> qua Ví MoMo.</p>
                
                <h3 style='border-bottom: 2px solid #eee; padding-bottom: 10px; color: #555; margin-top: 20px;'>CHI TIẾT ĐƠN HÀNG</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    $listItemsHTML
                </table>

                <p style='text-align: right; margin-top: 20px; font-size: 18px;'>
                    Tổng thanh toán: <b style='color: #d9534f;'>$tongTienFormat VNĐ</b>
                </p>

                <div style='text-align: center; margin-top: 30px;'>
                    <a href='$linkDonHang' style='background: #333; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Xem đơn hàng</a>
                </div>
                
                <hr style='border: 0; border-top: 1px solid #eee; margin: 30px 0;'>
                <p style='font-size: 12px; color: #999; text-align: center;'>Cảm ơn bạn đã tin tưởng EVA SHOP.</p>
            </div>
        </div>";

        $mail->send();
    } catch (Exception $e) {}
}

// =================================================================
// XỬ LÝ KẾT QUẢ TỪ MOMO
// =================================================================
if (isset($_GET['partnerCode']) && isset($_GET['resultCode'])) {
    $momoOrderId = $_GET['orderId']; 
    $parts = explode('_', $momoOrderId);
    $id_don = intval($parts[0]); 

    if ($_GET['resultCode'] == '0') {
        $conn->query("UPDATE donhang SET trang_thai='Đã thanh toán', phuong_thuc_tt='MOMO' WHERE id=$id_don");
        $message = "THANH TOÁN MOMO THÀNH CÔNG!";
        $sub_msg = "Giao dịch đã được xác nhận tự động.";
        $icon_img = "https://avatars.githubusercontent.com/u/36770798?s=200&v=4";
        $alert_class = "alert-success";
        guiMailMoMoDep($id_don, $conn, $base_url);
    } else {
        $message = "GIAO DỊCH MOMO BỊ HỦY!";
        $sub_msg = "Bạn chưa hoàn tất thanh toán hoặc giao dịch bị lỗi.";
        $icon_img = "https://cdn-icons-png.flaticon.com/512/1828/1828843.png"; 
        $alert_class = "alert-danger";
    }
}

// =================================================================
// XỬ LÝ KẾT QUẢ KHÁCH ĐẶT COD / CHUYỂN KHOẢN QR (SEPAY)
// =================================================================
elseif (isset($_GET['id'])) {
    $id_don = intval($_GET['id']);
    
    // NẾU LÀ ĐƠN HÀNG VỪA QUÉT QR THÀNH CÔNG (Có cờ send_mail)
    if (isset($_GET['send_mail']) && $_GET['send_mail'] == 1) {
        
        $message = "THANH TOÁN QR THÀNH CÔNG!";
        $sub_msg = "Hệ thống đã nhận được tiền và tự động chốt đơn. Cám ơn bạn!";
        $alert_class = "alert-success";

        // THUẬT TOÁN CHỐNG SPAM MAIL (Chỉ gửi 1 lần duy nhất)
        if (!isset($_SESSION['mail_sent_' . $id_don])) {
            
            // 1. Lấy thông tin đơn hàng
            $sql_don = "SELECT d.*, t.email FROM donhang d JOIN taikhoan t ON d.username = t.username WHERE d.id = $id_don";
            $rs_don = $conn->query($sql_don);
            
            if ($rs_don->num_rows > 0) {
                $donhang = $rs_don->fetch_assoc();
                
                // 2. Lấy chi tiết sản phẩm để nhét vào mail
                $ds_san_pham_mua = [];
                $sql_ct = "SELECT c.so_luong as sl, c.don_gia as gia, s.ten_sanpham as ten, s.hinh_anh as hinh, s.id 
                           FROM chitietdonhang c JOIN sanpham s ON c.sanpham_id = s.id 
                           WHERE c.donhang_id = $id_don";
                $rs_ct = $conn->query($sql_ct);
                while ($row = $rs_ct->fetch_assoc()) {
                    $ds_san_pham_mua[] = $row;
                }
                
                // 3. Gửi mail
                if (!empty($donhang['email'])) {
                    guiMailXacNhan($donhang['email'], $donhang['hoten_nguoinhan'], $id_don, $ds_san_pham_mua, $donhang['tong_tien'], $donhang['phuong_thuc_tt']);
                }
                
                // 4. Đóng dấu đã gửi vào Session
                $_SESSION['mail_sent_' . $id_don] = true;
            }
        }
    }
} else {
    header("Location: ../index.php"); 
    exit(); 
}

// Lấy thông tin chi tiết đơn hàng để hiển thị ra màn hình
if ($id_don > 0) {
    $sql = "SELECT * FROM donhang WHERE id=$id_don";
    $query = $conn->query($sql);
    $don = $query->fetch_assoc();

    if(isset($_GET['resultCode']) && $_GET['resultCode'] == '0') {
        $don['trang_thai'] = 'Đã thanh toán';
        $don['phuong_thuc_tt'] = 'MOMO';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả đặt hàng</title>
    <?php include '../includes/EVA_system_header.php'; ?>
</head>
<body class="bg-light">
    
    <div class="container text-center mt-5 mb-5">
        <div class="card shadow-sm p-5 border-0 mx-auto" style="max-width: 600px;">
            
            <div class="mb-4">
                <img src="<?=$icon_img?>" width="80" style="object-fit: contain;">
            </div>
            
            <h2 class="<?=$alert_class == 'alert-danger' ? 'text-danger' : ($alert_class == 'alert-success' ? 'text-success' : 'text-warning')?> fw-bold mb-3"><?=$message?></h2>
            <p class="text-muted"><?=$sub_msg?></p>

            <?php if(isset($don)): ?>
            <div class="alert <?=$alert_class?> mt-3 text-start">
                Mã đơn hàng: <strong>#<?=$id_don?></strong><br>
                Tổng tiền: <strong><?=number_format($don['tong_tien'])?> ₫</strong><br>
                Trạng thái: 
                <?php 
                    if($don['trang_thai'] == 'Đã thanh toán') 
                        echo '<span class="badge bg-success">Đã thanh toán</span>';
                    else 
                        echo '<span class="badge bg-secondary">'.$don['trang_thai'].'</span>';
                ?>
                <br>
                Phương thức: <strong><?=$don['phuong_thuc_tt']?></strong>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="../index.php" class="btn btn-outline-primary me-2">Về trang chủ</a>
                <a href="../product/EVA_product_list.php" class="btn btn-warning fw-bold">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>

    <?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>