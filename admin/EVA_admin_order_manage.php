<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}
include 'EVA_admin_db.php';

// Xóa đơn hàng
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Xóa chi tiết trước
    mysqli_query($conn, "DELETE FROM chitietdonhang WHERE donhang_id=$id");
    // Xóa đơn hàng
    mysqli_query($conn, "DELETE FROM donhang WHERE id=$id");
    header("Location: EVA_admin_order_manage.php");
    exit();
}

// Tìm kiếm
$where = "WHERE 1=1";
$kw = "";
if(isset($_GET['kw']) && !empty($_GET['kw'])) {
    $kw = mysqli_real_escape_string($conn, $_GET['kw']);
    $where .= " AND (id = '".intval($kw)."' OR hoten_nguoinhan LIKE '%$kw%')";
}

$sql = "SELECT * FROM donhang $where ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<?php include 'EVA_admin_menu.php'; ?>

<div class="container mb-5">
    <h3 class="mb-3 text-primary fw-bold text-uppercase border-start border-5 border-primary ps-3">Quản lý đơn hàng</h3>

    <div class="card p-3 mb-4 shadow-sm border-0">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="kw" class="form-control" placeholder="Nhập mã đơn (VD: 12) hoặc tên khách..." value="<?=$kw?>">
            <button class="btn btn-primary px-4 fw-bold"><i class="fas fa-search"></i> Tìm</button>
            <a href="EVA_admin_order_manage.php" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="fw-bold text-center">#<?=$row['id']?></td>
                            <td>
                                <span class="fw-bold text-dark"><?=$row['hoten_nguoinhan']?></span><br>
                                <span class="small text-muted"><i class="fas fa-phone-alt me-1"></i> <?=$row['sdt_nguoinhan']?></span>
                            </td>
                            <td><?=date('d/m/Y H:i', strtotime($row['ngay_dat']))?></td>
                            <td class="text-danger fw-bold"><?=number_format($row['tong_tien'])?> ₫</td>
                            <td>
                                <?php if($row['phuong_thuc_tt'] == 'MOMO'): ?>
                                    <span class="badge" style="background-color: #a50064;">MOMO</span>
                                <?php elseif($row['phuong_thuc_tt'] == 'BANK'): ?>
                                    <span class="badge bg-primary">BANK</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">COD</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $s = $row['trang_thai'];
                                    $cls = 'secondary';
                                    if($s=='Mới') $cls='info';
                                    if($s=='Đã thanh toán' || $s=='Hoàn thành') $cls='success';
                                    if($s=='Hủy') $cls='danger';
                                    if($s=='Đang giao') $cls='warning text-dark';
                                ?>
                                <span class="badge bg-<?=$cls?>"><?=$s?></span>
                            </td>
                            <td class="text-center">
                                <a href="EVA_admin_order_detail.php?id=<?=$row['id']?>" class="btn btn-primary btn-sm" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                <a href="EVA_admin_order_manage.php?action=delete&id=<?=$row['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?')" title="Xóa"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>