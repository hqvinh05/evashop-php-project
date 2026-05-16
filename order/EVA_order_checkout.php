<?php
session_start();
include_once '../includes/EVA_system_db.php';

// --- GỌI FILE THANH TOÁN MOMO ---
if (file_exists('../cart/EVA_payment_momo.php')) include_once '../cart/EVA_payment_momo.php';

// --- INCLUDE PHPMAILER ---
require '../mail/PHPMailer/PHPMailer.php';
require '../mail/PHPMailer/Exception.php';
require '../mail/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['username'])) { header('Location: ../auth/EVA_auth_login.php'); exit(); }
$u = $_SESSION['username'];
$base_url = "http://localhost/EVASHOP"; 

// --- HÀM GỬI MAIL "XỊN" ---
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

// 1. TÍNH GIỎ HÀNG
$tong_tien_hang = 0;
$ds_san_pham_mua = []; 
function themVaoDanhSach(&$list, $sp, $sl) { $list[] = ['id' => $sp['id'], 'ten' => $sp['ten_sanpham'], 'ma' => $sp['ma_sp'], 'hinh' => $sp['hinh_anh'], 'sl' => $sl, 'gia' => $sp['gia']]; }

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']); $sl = isset($_GET['sl']) ? intval($_GET['sl']) : 1;
    $sp = $conn->query("SELECT * FROM sanpham WHERE id=$id")->fetch_assoc();
    if ($sp) { $tong_tien_hang = $sp['gia'] * $sl; themVaoDanhSach($ds_san_pham_mua, $sp, $sl); }
} else {
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $ids = implode(',', array_keys($_SESSION['cart']));
        if(!empty($ids)){
            $result_cart = $conn->query("SELECT * FROM sanpham WHERE id IN ($ids)");
            while($sp = $result_cart->fetch_assoc()){
                $sl = $_SESSION['cart'][$sp['id']]; if(is_array($sl)) $sl = 1;
                $tong_tien_hang += ($sp['gia'] * $sl); themVaoDanhSach($ds_san_pham_mua, $sp, $sl);
            }
        }
    }
}
if (empty($ds_san_pham_mua)) { echo "<script>window.location='../product/EVA_product_list.php';</script>"; exit(); }

$user_info = $conn->query("SELECT * FROM taikhoan WHERE username='$u'")->fetch_assoc();
$diem_hien_co = intval($user_info['diem_tich_luy']);
$email_khach = $user_info['email']; 

// --- LẤY DANH SÁCH VOUCHER ---
$sql_vouchers = "SELECT * FROM kho_voucher WHERE username='$u' AND trang_thai=0 ORDER BY id DESC";
$rs_vouchers = $conn->query($sql_vouchers);
$my_vouchers = [];
if ($rs_vouchers->num_rows > 0) { while($row = $rs_vouchers->fetch_assoc()) { $my_vouchers[] = $row; } }

// --- XỬ LÝ ĐẶT HÀNG ---
if (isset($_POST['btnConfirm'])) {
    $hoten = $_POST['hoten']; $sdt = $_POST['sdt']; $diachi = $_POST['diachi']; $pttt = $_POST['pttt'];
    
    // Xử lý Voucher & Xu
    $voucher_code = isset($_POST['voucher_code_input']) ? $conn->real_escape_string($_POST['voucher_code_input']) : '';
    $giam_voucher = 0;
    if(!empty($voucher_code)) {
        $check_v = $conn->query("SELECT * FROM kho_voucher WHERE ma_code='$voucher_code' AND username='$u' AND trang_thai=0");
        if($check_v->num_rows > 0) { $v_data = $check_v->fetch_assoc(); $giam_voucher = $v_data['gia_tri']; }
    }

    $tien_sau_voucher = $tong_tien_hang - $giam_voucher;
    if($tien_sau_voucher < 0) $tien_sau_voucher = 0;

    $dung_xu = isset($_POST['use_coin']) ? true : false;
    $giam_xu = 0;
    if ($dung_xu && $diem_hien_co > 0) {
        if ($diem_hien_co >= $tien_sau_voucher) $giam_xu = $tien_sau_voucher;
        else $giam_xu = $diem_hien_co;
    }

    $tong_thanh_toan = $tong_tien_hang - $giam_voucher - $giam_xu;
    $trang_thai_don = ($pttt == 'MOMO') ? 'Chờ thanh toán' : 'Mới';

    // INSERT ĐƠN HÀNG
    $sql_don = "INSERT INTO donhang (username, hoten_nguoinhan, sdt_nguoinhan, diachi_nguoinhan, tong_tien, phuong_thuc_tt, trang_thai, ngay_dat) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql_don);
    $stmt->bind_param("sssssss", $u, $hoten, $sdt, $diachi, $tong_thanh_toan, $pttt, $trang_thai_don);
    
    if ($stmt->execute()) {
        $iddon = $stmt->insert_id;
        
        $stmt_ct = $conn->prepare("INSERT INTO chitietdonhang (donhang_id, sanpham_id, so_luong, don_gia) VALUES (?, ?, ?, ?)");
        
        foreach ($ds_san_pham_mua as $item) {
            $stmt_ct->bind_param("iiid", $iddon, $item['id'], $item['sl'], $item['gia']);
            $stmt_ct->execute();
            $conn->query("UPDATE sanpham SET so_luong = so_luong - {$item['sl']} WHERE id = {$item['id']}");
        }
        
        if ($giam_xu > 0) $conn->query("UPDATE taikhoan SET diem_tich_luy = diem_tich_luy - $giam_xu WHERE username = '$u'");
        if ($giam_voucher > 0) $conn->query("UPDATE kho_voucher SET trang_thai = 1 WHERE ma_code = '$voucher_code'");

        // --- PHÂN LUỒNG ---

        // 1. MOMO
        if ($pttt == 'MOMO') {
            unset($_SESSION['cart']); 
            thanhToanMoMo($iddon, $tong_thanh_toan); 
            exit();
        }

        // 2. COD/BANK
        else {
            // FIX LỖI 1: CHỈ GỬI MAIL NẾU LÀ COD, NẾU BANK THÌ BỎ QUA KHÔNG GỬI
            if (!empty($email_khach) && $pttt == 'COD') {
                guiMailXacNhan($email_khach, $hoten, $iddon, $ds_san_pham_mua, $tong_thanh_toan, $pttt);
            }
            if (!isset($_GET['id'])) unset($_SESSION['cart']);
            
            // --- XỬ LÝ THANH TOÁN QR (BANK) ---
            if ($pttt == 'BANK') {
                $bankId = "970423"; // Mã BIN TPBank
                $accountNo = "99881628899"; // STK của bạn
                $accountName = "HUYNH QUOC VINH"; 
                $amount = $tong_thanh_toan; 
                $description = "EVASHOP DH" . $iddon; 

                $postData = json_encode(array(
                    "accountNo" => $accountNo,
                    "accountName" => $accountName,
                    "acqId" => $bankId,
                    "amount" => $amount,
                    "addInfo" => $description,
                    "format" => "text",
                    "template" => "compact"
                ));

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.vietqr.io/v2/generate",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => $postData,
                  CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                $qrDataURL = "";
                if (!$err) {
                    $result = json_decode($response, true);
                    if (isset($result['code']) && $result['code'] == "00") {
                        $qrDataURL = $result['data']['qrDataURL'];
                    }
                }

                // Hiển thị giao diện quét mã QR
                ?>
                <!DOCTYPE html>
                <html lang="vi">
                <head>
                    <meta charset="UTF-8">
                    <title>Thanh toán QR - EVA SHOP</title>
                    <?php include '../includes/EVA_system_header.php'; ?>
                </head>
                <body class="bg-light">
                    <div class="container mt-5 mb-5">
                        <div class="qr-payment-container bg-white" style="text-align: center; margin: 30px auto; padding: 40px; border: 1px solid #ddd; border-radius: 15px; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                            <h3 style="color: #333; margin-bottom: 20px; font-weight: bold;"><i class="fas fa-qrcode text-primary"></i> Quét mã để thanh toán</h3>
                            <p class="text-muted">Đơn hàng <strong>#<?php echo $iddon; ?></strong> đã được lưu. Vui lòng hoàn tất thanh toán.</p>
                            
                            <?php if (!empty($qrDataURL)): ?>
                                <img src="<?php echo $qrDataURL; ?>" alt="Mã QR Thanh Toán" style="max-width: 100%; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin: 15px 0;">
                                
                                <div style="margin-top: 20px; text-align: left; background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 5px solid #ffc107;">
                                    <p style="margin: 8px 0; font-size: 16px;">Ngân hàng: <strong>TPBank</strong></p>
                                    <p style="margin: 8px 0; font-size: 16px;">Chủ TK: <strong>HUYNH QUOC VINH</strong></p>
                                    <p style="margin: 8px 0; font-size: 16px;">Số tiền: <strong style="color: red; font-size: 22px;"><?php echo number_format($amount, 0, ',', '.'); ?> VNĐ</strong></p>
                                    <p style="margin: 8px 0; font-size: 16px;">Nội dung: <strong><?php echo $description; ?></strong></p>
                                </div>
                                
                                <form action="../page/EVA_page_order_success.php" method="GET" style="margin-top: 30px;" id="frmPayment">
                                    <input type="hidden" name="id" value="<?php echo $iddon; ?>">
                                    <input type="hidden" name="type" value="bank">
                                    <input type="hidden" name="send_mail" value="1">
                                    <button type="submit" id="btnCheckPayment" class="btn btn-secondary w-100 py-3 fw-bold shadow-sm" style="font-size: 18px;" disabled>
                                        <i class="fas fa-spinner fa-spin"></i> Đang chờ nhận tiền...
                                    </button>
                                </form>

                                <script>
                                    let orderId = <?php echo $iddon; ?>;
                                    let amount = <?php echo $amount; ?>;
                                    let btnSubmit = document.getElementById('btnCheckPayment');
                                    let checkCount = 0;
                                    let maxChecks = 60; // Quét 5 phút 

                                    let checkInterval = setInterval(function() {
                                        checkCount++;
                                        if (checkCount > maxChecks) {
                                            clearInterval(checkInterval);
                                            btnSubmit.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Hết thời gian chờ. Bấm để tự xác nhận';
                                            btnSubmit.disabled = false;
                                            btnSubmit.classList.replace('btn-secondary', 'btn-danger');
                                            return;
                                        }

                                        let formData = new FormData();
                                        formData.append('order_id', orderId);
                                        formData.append('amount', amount);
                                        
                                        fetch('../api/EVA_api_check_payment.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if(data.status === 'success') {
                                                clearInterval(checkInterval); 
                                                btnSubmit.innerHTML = '<i class="fas fa-check-circle"></i> Đã nhận tiền thành công!';
                                                btnSubmit.classList.replace('btn-secondary', 'btn-success');
                                                
                                                setTimeout(() => {
                                                    document.getElementById('frmPayment').submit();
                                                }, 1500);
                                            }
                                        })
                                        .catch(err => console.log('Đang kết nối...'));
                                    }, 5000); 
                                </script>

                            <?php else: ?>
                                <div class="alert alert-danger mt-4">Hệ thống đang lỗi, không thể tạo mã QR lúc này.</div>
                                <a href="../page/EVA_page_order_success.php?id=<?php echo $iddon; ?>&type=bank&send_mail=1" class="btn btn-warning mt-3 py-2 w-100 fw-bold">Tiếp tục</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php include '../includes/EVA_system_footer.php'; ?>
                </body>
                </html>
                <?php
                exit(); // Thoát để không tải giao diện checkout bên dưới
            } else {
                header("Location: ../page/EVA_page_order_success.php?id=$iddon");
                exit();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - EVA SHOP</title>
    <?php include '../includes/EVA_system_header.php'; ?>
    <style>
        .payment-option { cursor: pointer; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; display: block; margin-bottom: 10px; background: #fff; transition: 0.2s; }
        .payment-option:hover { border-color: #ffc107; background: #fffdf5; }
        .payment-radio { float: left; margin-top: 5px; margin-right: 10px; accent-color: #ffc107; }
        .coin-box { background: #fff3cd; padding: 15px; border-radius: 8px; border: 1px dashed #ffc107; }
        .voucher-box { background: #e3f2fd; padding: 15px; border-radius: 8px; border: 1px dashed #2196f3; }
        .voucher-item { cursor: pointer; border: 1px solid #eee; border-left: 5px solid #ffc107; margin-bottom: 10px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .voucher-item:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); background: #fff9db; }
        .modal-content { border-radius: 15px; border: none; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .modal-header { border-bottom: none; padding: 20px; }
        .modal-body { padding: 20px; background: #f8f9fa; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4 text-uppercase fw-bold border-bottom pb-3 d-inline-block mx-auto" style="border-bottom: 3px solid #ffc107 !important;">XÁC NHẬN ĐƠN HÀNG</h2>
        <form method="post">
            <div class="row">
                <div class="col-md-7">
                    <div class="card p-4 shadow-sm mb-4 border-0 rounded-3">
                        <h5 class="text-primary mb-3 fw-bold"><i class="fas fa-map-marker-alt"></i> Thông tin nhận hàng</h5>
                        <?php if(!empty($user_info['dia_chi'])): ?>
                        <div class="form-check mb-3 bg-light p-3 rounded border">
                            <input class="form-check-input" type="checkbox" id="use_default_addr" onchange="toggleAddress()">
                            <label class="form-check-label fw-bold" for="use_default_addr">Sử dụng địa chỉ mặc định</label>
                            <div class="small text-muted mt-1"><?=$user_info['hoten']?> - <?=$user_info['so_dien_thoai']?><br><?=$user_info['dia_chi']?></div>
                        </div>
                        <?php endif; ?>
                        <div class="form-floating mb-3"><input name="hoten" id="hoten" class="form-control" placeholder="Họ tên" required><label>Họ và tên</label></div>
                        <div class="form-floating mb-3"><input name="sdt" id="sdt" class="form-control" placeholder="SĐT" required><label>Số điện thoại</label></div>
                        <div class="form-floating"><textarea name="diachi" id="diachi" class="form-control" placeholder="Địa chỉ" style="height: 100px" required></textarea><label>Địa chỉ</label></div>
                    </div>
                    
                    <div class="card p-4 shadow-sm border-0 rounded-3">
                        <h5 class="text-success mb-3 fw-bold"><i class="fas fa-credit-card"></i> Phương thức thanh toán</h5>
                        <label class="payment-option"><input type="radio" name="pttt" value="COD" checked class="payment-radio"><div class="d-flex align-items-center"><i class="fas fa-money-bill-wave text-success fa-2x me-3"></i><div><strong>COD</strong><br><small class="text-muted">Thanh toán khi nhận hàng.</small></div></div></label>
                        <label class="payment-option"><input type="radio" name="pttt" value="BANK" class="payment-radio"><div class="d-flex align-items-center"><i class="fas fa-qrcode text-primary fa-2x me-3"></i><div><strong>Chuyển khoản QR</strong><br><small class="text-muted">Quét mã VietQR.</small></div></div></label>
                        <label class="payment-option"><input type="radio" name="pttt" value="MOMO" class="payment-radio"><div class="d-flex align-items-center"><img src="../images/momo.png" width="40" class="me-3 rounded"><div><strong>Ví MoMo</strong><br><small class="text-muted">Cổng thanh toán MoMo.</small></div></div></label>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="card p-4 shadow-sm border-warning rounded-3" style="border-width:2px">
                        <h5 class="text-danger mb-3 fw-bold"><i class="fas fa-shopping-cart"></i> Đơn hàng (<?=count($ds_san_pham_mua)?> món)</h5>
                        <div style="max-height: 250px; overflow-y: auto;" class="mb-3 custom-scroll">
                            <?php foreach($ds_san_pham_mua as $item): ?>
                            <div class="d-flex gap-3 mb-3 border-bottom pb-2">
                                <img src="../<?=tim_hinh_anh($item['hinh'])?>" width="60" class="rounded border">
                                <div><h6 class="fw-bold mb-1"><?=$item['ten']?></h6><small class="text-muted">SL: <b><?=$item['sl']?></b> x <?=number_format($item['gia'])?></small></div>
                                <div class="ms-auto fw-bold text-danger"><?=number_format($item['sl']*$item['gia'])?> ₫</div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="voucher-box mb-3">
                            <label class="fw-bold mb-2 text-primary d-flex justify-content-between">
                                <span><i class="fas fa-ticket-alt"></i> Mã Giảm Giá</span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#voucherModal" class="small text-decoration-none fw-bold"><i class="fas fa-list"></i> Chọn mã có sẵn</a>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="voucher_input" placeholder="Nhập mã hoặc chọn...">
                                <button class="btn btn-primary fw-bold" type="button" onclick="checkVoucher()">Áp dụng</button>
                            </div>
                            <small id="voucher_msg" class="text-danger mt-1 d-block"></small>
                            <input type="hidden" name="voucher_code_input" id="voucher_code_hidden">
                        </div>

                        <div class="coin-box mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div><i class="fas fa-coins text-warning"></i> Ví: <b><?=number_format($diem_hien_co)?> xu</b></div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="use_coin" id="use_coin" onchange="calcTotal()">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2"><span>Tạm tính:</span><span class="fw-bold"><?=number_format($tong_tien_hang)?> ₫</span></div>
                        <div class="d-flex justify-content-between mb-2 text-primary" id="row_voucher" style="display:none;"><span><i class="fas fa-ticket-alt"></i> Voucher:</span><span>-<span id="val_voucher">0</span> ₫</span></div>
                        <div class="d-flex justify-content-between mb-2 text-success" id="row_coin" style="display:none;"><span><i class="fas fa-coins"></i> Xu:</span><span>-<span id="val_coin">0</span> ₫</span></div>
                        
                        <div class="d-flex justify-content-between fs-4 fw-bold mb-4 border-top pt-3">
                            <span>Thành tiền:</span>
                            <span class="text-danger" id="final_total"><?=number_format($tong_tien_hang)?> ₫</span>
                        </div>
                        
                        <button name="btnConfirm" class="btn btn-warning w-100 fw-bold py-3 text-uppercase shadow-sm"><i class="fas fa-check-circle me-2"></i> ĐẶT HÀNG</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="voucherModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-gift"></i> KHO VOUCHER CỦA BẠN</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if(count($my_vouchers) > 0): ?>
                        <p class="small text-muted mb-3">Chọn 1 mã để áp dụng ngay:</p>
                        <?php foreach($my_vouchers as $v): ?>
                            <div class="voucher-item d-flex justify-content-between align-items-center" onclick="selectVoucher('<?=$v['ma_code']?>')">
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark"><?=$v['ten_voucher']?></h6>
                                    <div class="small text-muted"><i class="fas fa-barcode"></i> Mã: <b><?=$v['ma_code']?></b></div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger fs-5">-<?=number_format($v['gia_tri']/1000)?>K</div>
                                    <button class="btn btn-sm btn-outline-primary mt-1">Dùng ngay</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="mb-3 opacity-50">
                            <p class="text-muted fw-bold">Bạn chưa có voucher nào!</p>
                            <a href="../game/EVA_game_wheel.php" class="btn btn-sm btn-primary">Săn voucher ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        var totalOrigin = <?=$tong_tien_hang?>;
        var userBalance = <?=$diem_hien_co?>;
        var discountVoucher = 0;

        function toggleAddress() {
            if(document.getElementById('use_default_addr').checked) {
                document.getElementById('hoten').value = "<?=$user_info['hoten']?>";
                document.getElementById('sdt').value = "<?=$user_info['so_dien_thoai']?>";
                document.getElementById('diachi').value = "<?=$user_info['dia_chi']?>";
            } else {
                document.getElementById('hoten').value = ""; document.getElementById('sdt').value = ""; document.getElementById('diachi').value = "";
            }
        }
        function selectVoucher(code) {
            document.getElementById('voucher_input').value = code;
            bootstrap.Modal.getInstance(document.getElementById('voucherModal')).hide();
            checkVoucher();
        }
        function checkVoucher() {
            var code = document.getElementById('voucher_input').value;
            var msg = document.getElementById('voucher_msg');
            if(!code) return;
            var formData = new FormData(); formData.append('code', code);
            fetch('../api/EVA_api_check_voucher.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status == 'success') {
                    discountVoucher = parseInt(data.discount);
                    msg.className = 'text-success mt-1 d-block fw-bold'; 
                    msg.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    document.getElementById('voucher_code_hidden').value = code;
                    document.getElementById('voucher_input').disabled = true;
                    calcTotal();
                } else {
                    discountVoucher = 0;
                    msg.className = 'text-danger mt-1 d-block fw-bold'; 
                    msg.innerHTML = '<i class="fas fa-times-circle"></i> ' + data.message;
                    calcTotal();
                }
            });
        }
        function calcTotal() {
            var afterVoucher = totalOrigin - discountVoucher;
            if(afterVoucher < 0) afterVoucher = 0;
            document.getElementById('row_voucher').style.display = (discountVoucher > 0) ? 'flex' : 'none';
            document.getElementById('val_voucher').innerText = new Intl.NumberFormat().format(discountVoucher);

            var discountCoin = 0;
            if(document.getElementById('use_coin').checked) {
                discountCoin = (userBalance >= afterVoucher) ? afterVoucher : userBalance;
                document.getElementById('row_coin').style.display = 'flex';
                document.getElementById('val_coin').innerText = new Intl.NumberFormat().format(discountCoin);
            } else {
                document.getElementById('row_coin').style.display = 'none';
            }
            var final = afterVoucher - discountCoin;
            document.getElementById('final_total').innerText = new Intl.NumberFormat().format(final) + ' ₫';
        }
    </script>
    <?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>