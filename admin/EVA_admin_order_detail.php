<?php
session_start();

// 1. Kiểm tra Admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 1) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}

// 2. Kết nối CSDL (Dùng file gốc ở includes)
include '../includes/EVA_system_db.php';

$id_don = isset($_GET['id']) ? intval($_GET['id']) : 0;

// --- HÀM TÌM ẢNH ADMIN (Đã fix) ---
function admin_tim_anh($filename) {
    if (empty($filename)) return '../images/no-image.png';
    // File ảnh nằm ở thư mục gốc images -> từ admin phải ra ngoài 1 cấp
    return '../' . tim_hinh_anh($filename); // Tận dụng hàm có sẵn trong EVA_system_db.php
}

// --- CẬP NHẬT TRẠNG THÁI ---
if(isset($_POST['btnUpdateStatus'])) {
    $status = $_POST['trang_thai'];
    $stmt = $conn->prepare("UPDATE donhang SET trang_thai=? WHERE id=?");
    $stmt->bind_param("si", $status, $id_don);
    if($stmt->execute()) {
        echo "<script>alert('Đã cập nhật trạng thái!'); window.location.href='EVA_admin_order_detail.php?id=$id_don';</script>";
    }
}

// --- LẤY INFO ĐƠN HÀNG ---
$query_don = mysqli_query($conn, "SELECT * FROM donhang WHERE id=$id_don");
if(mysqli_num_rows($query_don) > 0) {
    $don = mysqli_fetch_assoc($query_don);
} else {
    die("Không tìm thấy đơn hàng!");
}

// --- LẤY CHI TIẾT ---
$sql_ct = "SELECT ct.*, s.ten_sanpham, s.hinh_anh, s.ma_sp FROM chitietdonhang ct LEFT JOIN sanpham s ON ct.sanpham_id = s.id WHERE ct.donhang_id=$id_don";
$query_ct = mysqli_query($conn, $sql_ct);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn #<?=$id_don?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>.img-product { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd; }</style>
</head>
<body class="bg-light">

<?php include 'EVA_admin_menu.php'; ?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-uppercase border-start border-5 border-primary ps-3">Chi tiết đơn hàng #<?=$id_don?></h3>
        <a href="EVA_admin_order_manage.php" class="btn btn-outline-secondary fw-bold"><i class="fas fa-arrow-left me-2"></i> Quay lại</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-primary text-white fw-bold"><i class="fas fa-user me-2"></i> Thông tin khách hàng</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0"><small class="text-muted">Người nhận:</small><br><strong><?=$don['hoten_nguoinhan']?></strong></li>
                        <li class="list-group-item px-0"><small class="text-muted">SĐT:</small><br><strong><?=$don['sdt_nguoinhan']?></strong></li>
                        <li class="list-group-item px-0"><small class="text-muted">Địa chỉ:</small><br><span><?=$don['diachi_nguoinhan']?></span></li>
                        <li class="list-group-item px-0"><small class="text-muted">Username:</small><br><span class="badge bg-secondary"><?=($don['username'] ?? 'Vãng lai')?></span></li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning fw-bold text-dark"><i class="fas fa-edit me-2"></i> Cập nhật trạng thái</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Trạng thái xử lý</label>
                            <select name="trang_thai" class="form-select fw-bold">
                                <option value="Chờ thanh toán" <?=$don['trang_thai']=='Chờ thanh toán'?'selected':''?>>Chờ thanh toán</option>
                                <option value="Đã thanh toán" <?=$don['trang_thai']=='Đã thanh toán'?'selected':''?>>Đã thanh toán (QR/MoMo)</option>
                                <option value="Mới" <?=$don['trang_thai']=='Mới'?'selected':''?>>Mới (COD)</option>
                                <option value="Đang giao" <?=$don['trang_thai']=='Đang giao'?'selected':''?>>Đang giao hàng</option>
                                <option value="Hoàn thành" <?=$don['trang_thai']=='Hoàn thành'?'selected':''?>>Hoàn thành</option>
                                <option value="Hủy" <?=$don['trang_thai']=='Hủy'?'selected':''?> class="text-danger">Hủy đơn hàng</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Phương thức TT</label>
                            <input type="text" class="form-control bg-light" value="<?=$don['phuong_thuc_tt']?>" readonly>
                        </div>
                        <button type="submit" name="btnUpdateStatus" class="btn btn-success w-100 fw-bold"><i class="fas fa-save me-2"></i> LƯU THAY ĐỔI</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between">
                    <span><i class="fas fa-box-open me-2"></i> Danh sách sản phẩm</span>
                    <span>SL: <?=mysqli_num_rows($query_ct)?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end pe-3">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $tam_tinh = 0;
                                while($row = mysqli_fetch_assoc($query_ct)): 
                                    $thanh_tien_sp = $row['don_gia'] * $row['so_luong'];
                                ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <img src="<?=admin_tim_anh($row['hinh_anh'])?>" class="img-product me-3">
                                            <div>
                                                <div class="fw-bold"><?=$row['ten_sanpham']?></div>
                                                <small class="text-muted">Mã: <?=$row['ma_sp']?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?=number_format($row['don_gia'])?> ₫</td>
                                    <td class="text-center fw-bold"><?=$row['so_luong']?></td>
                                    <td class="text-end pe-3 fw-bold"><?=number_format($thanh_tien_sp)?> ₫</td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold pt-3 fs-5">TỔNG CỘNG:</td>
                                    <td class="text-end pe-3 pt-3"><h4 class="text-danger fw-bold"><?=number_format($don['tong_tien'])?> ₫</h4></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>