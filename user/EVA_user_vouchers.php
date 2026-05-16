<?php
session_start();
include '../includes/EVA_system_db.php';
if (!isset($_SESSION['username'])) { header("Location: ../auth/EVA_auth_login.php"); exit(); }
$u = $_SESSION['username'];
$rs = $conn->query("SELECT * FROM kho_voucher WHERE username='$u' ORDER BY trang_thai ASC, id DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kho Voucher - EVA SHOP</title>
    <?php include '../includes/EVA_system_header.php'; ?>
    <style>
        .voucher-ticket {
            background: #fff; border: 2px dashed #ffc107; border-radius: 12px;
            display: flex; margin-bottom: 15px; overflow: hidden; position: relative;
        }
        .v-left {
            background: #ffc107; width: 80px; display: flex; align-items: center; 
            justify-content: center; font-weight: bold; font-size: 20px; color: #000;
            border-right: 2px dashed #fff;
        }
        .v-right { padding: 15px; flex-grow: 1; }
        .v-code { font-family: monospace; color: #d63031; background: #eee; padding: 2px 5px; font-weight: bold; }
        .v-used { filter: grayscale(1); opacity: 0.6; }
        .v-badge { position: absolute; top: 10px; right: 10px; font-size: 10px; background: #ccc; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <h3 class="fw-bold mb-4"><i class="fas fa-ticket-alt text-warning"></i> KHO VOUCHER CỦA TÔI</h3>
    <div class="row">
        <?php while($v = $rs->fetch_assoc()): $used = ($v['trang_thai']==1); ?>
        <div class="col-md-6">
            <div class="voucher-ticket <?=$used?'v-used':''?>">
                <div class="v-left"><?=number_format($v['gia_tri']/1000)?>k</div>
                <div class="v-right">
                    <h5 class="fw-bold mb-1"><?=$v['ten_voucher']?></h5>
                    <div class="small mb-2">Mã: <span class="v-code"><?=$v['ma_code']?></span></div>
                    <small class="text-muted">Ngày nhận: <?=date('d/m/Y', strtotime($v['ngay_trung']))?></small>
                    <?php if($used): ?>
                        <span class="v-badge">Đã dùng</span>
                    <?php else: ?>
                        <a href="../cart/EVA_cart_view.php" class="btn btn-sm btn-warning float-end fw-bold">Dùng ngay</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>