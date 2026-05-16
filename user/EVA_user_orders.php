<?php
session_start();
// SỬA: Đường dẫn DB
include '../includes/EVA_system_db.php';

if(!isset($_SESSION['username'])) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}

$u = $_SESSION['username'];
$sql = "SELECT * FROM donhang WHERE username='$u' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <?php include '../includes/EVA_system_header.php'; ?>
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <h3 class="fw-bold mb-4 text-uppercase border-start border-5 border-primary ps-3">LỊCH SỬ ĐƠN HÀNG</h3>
    
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold">#<?=$row['id']?></td>
                                <td><?=date('d/m/Y', strtotime($row['ngay_dat']))?></td>
                                <td class="fw-bold text-danger"><?=number_format($row['tong_tien'])?>₫</td>
                                <td>
                                    <?php 
                                        $bg = 'secondary';
                                        if($row['trang_thai']=='Mới') $bg='primary';
                                        if($row['trang_thai']=='Hoàn thành' || $row['trang_thai']=='Đã thanh toán') $bg='success';
                                        if($row['trang_thai']=='Hủy') $bg='danger';
                                    ?>
                                    <span class="badge bg-<?=$bg?>"><?=$row['trang_thai']?></span>
                                </td>
                                <td class="text-center">
                                    <a href="../page/EVA_page_order_success.php?id=<?=$row['id']?>" class="btn btn-sm btn-outline-primary">Xem</a>
                                    
                                 <a href="EVA_user_reorder.php?order_id=<?= $row['id'] ?>" class="btn btn-warning btn-sm fw-bold">
    <i class="fas fa-cart-plus"></i> Mua lại
</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4">Bạn chưa có đơn hàng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>