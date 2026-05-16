<?php
session_start();
// 1. Check quyền Admin (GIỮ NGUYÊN)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}

// 2. Kết nối DB (GIỮ NGUYÊN)
include 'EVA_admin_db.php';

// 3. Xử lý xóa sản phẩm (GIỮ NGUYÊN)
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Xóa ảnh cũ (nếu muốn kỹ hơn thì thêm đoạn unlink)
    mysqli_query($conn, "DELETE FROM sanpham WHERE id = $id");
    header("Location: EVA_admin_product_manage.php");
    exit();
}

// 4. Tìm kiếm & Lọc (GIỮ NGUYÊN)
$where = "WHERE 1=1"; 
$keyword = "";
$cat_id = "";

if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $where .= " AND s.ten_sanpham LIKE '%$keyword%'";
}
if(isset($_GET['cat_id']) && !empty($_GET['cat_id'])) {
    $cat_id = intval($_GET['cat_id']);
    $where .= " AND s.danhmuc_id = $cat_id";
}

$sql = "SELECT s.*, d.ten_danhmuc, t.ten_thuonghieu 
        FROM sanpham s
        LEFT JOIN danhmuc d ON s.danhmuc_id = d.id
        LEFT JOIN thuonghieu t ON s.thuonghieu_id = t.id
        $where
        ORDER BY s.id DESC";
$result = mysqli_query($conn, $sql);
$cats = mysqli_query($conn, "SELECT * FROM danhmuc");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<?php include 'EVA_admin_menu.php'; ?>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary fw-bold text-uppercase border-start border-5 border-primary ps-3">Quản lý sản phẩm</h3>
        <a href="EVA_admin_product_add.php" class="btn btn-success fw-bold shadow-sm"><i class="fas fa-plus me-2"></i> Thêm mới</a>
    </div>

    <div class="card p-3 mb-4 shadow-sm border-0">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="keyword" class="form-control" placeholder="Nhập tên sản phẩm..." value="<?=$keyword?>">
            </div>
            <div class="col-md-4">
                <select name="cat_id" class="form-select">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php while($c = mysqli_fetch_assoc($cats)): ?>
                        <option value="<?=$c['id']?>" <?=$cat_id==$c['id']?'selected':''?>><?=$c['ten_danhmuc']?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100 fw-bold"><i class="fas fa-search"></i> Tìm</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá bán</th>
                            <th class="text-center">Kho</th>
                            <th>Danh mục</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td>#<?=$row['id']?></td>
                            <td>
                                <img src="../<?=tim_hinh_anh($row['hinh_anh'])?>" width="50" height="50" class="border rounded object-fit-cover">
                            </td>
                            <td class="fw-bold text-dark"><?=$row['ten_sanpham']?></td>
                            <td class="text-danger fw-bold"><?=number_format($row['gia'])?> ₫</td>
                            <td class="text-center">
                                <?php if($row['so_luong'] > 0): ?>
                                    <span class="badge bg-success"><?=$row['so_luong']?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Hết hàng</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-info text-dark"><?=$row['ten_danhmuc']?></span></td>
                            <td class="text-center">
                                <a href="EVA_admin_product_edit.php?id=<?=$row['id']?>" class="btn btn-primary btn-sm" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <a href="EVA_admin_product_manage.php?action=delete&id=<?=$row['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>