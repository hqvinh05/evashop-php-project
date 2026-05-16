<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}
include 'EVA_admin_db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten = $_POST['ten_sanpham'];
    $gia = floatval($_POST['gia']);
    $sl = intval($_POST['so_luong']);
    $thong_so = $_POST['thong_so'];
    $danhmuc = intval($_POST['danhmuc_id']);
    $thuonghieu = intval($_POST['thuonghieu_id']);
    $mota = $_POST['mo_ta'];

    // Xử lý upload ảnh
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['name'] != "") {
        $target_dir = "../images/"; // Lưu ra thư mục images ở gốc
        $file_name = basename($_FILES["hinh_anh"]["name"]);
        
        // Tránh trùng tên file bằng cách thêm timestamp
        $target_file_name = time() . "_" . $file_name;
        $target_file = $target_dir . $target_file_name;
        
        if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file)) {
            // Lưu tên file vào DB
            $sql = "INSERT INTO sanpham (ten_sanpham, gia, so_luong, thong_so, danhmuc_id, thuonghieu_id, hinh_anh, mo_ta, trang_thai) 
                    VALUES ('$ten', $gia, $sl, '$thong_so', $danhmuc, $thuonghieu, '$target_file_name', '$mota', 1)";
            
            if (mysqli_query($conn, $sql)) {
                $success = "Thêm sản phẩm thành công!";
            } else {
                $error = "Lỗi SQL: " . mysqli_error($conn);
            }
        } else {
            $error = "Không upload được ảnh. Kiểm tra quyền thư mục!";
        }
    } else {
        $error = "Vui lòng chọn ảnh đại diện!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<?php include 'EVA_admin_menu.php'; ?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-plus-circle me-2"></i> THÊM SẢN PHẨM MỚI
                </div>
                <div class="card-body">
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên sản phẩm</label>
                                <input type="text" name="ten_sanpham" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                                <input type="number" name="gia" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Số lượng kho</label>
                                <input type="number" name="so_luong" class="form-control" value="100" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Danh mục</label>
                                <select name="danhmuc_id" class="form-select">
                                    <?php 
                                    $dm = mysqli_query($conn, "SELECT * FROM danhmuc");
                                    while($d = mysqli_fetch_assoc($dm)) echo "<option value='{$d['id']}'>{$d['ten_danhmuc']}</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Thương hiệu</label>
                                <select name="thuonghieu_id" class="form-select">
                                    <?php 
                                    $th = mysqli_query($conn, "SELECT * FROM thuonghieu");
                                    while($t = mysqli_fetch_assoc($th)) echo "<option value='{$t['id']}'>{$t['ten_thuonghieu']}</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Thông số (Size/Trọng lượng)</label>
                                <input type="text" name="thong_so" class="form-control" placeholder="VD: 4U, Size 40...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện</label>
                            <input type="file" name="hinh_anh" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="mo_ta" class="form-control" rows="5"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success fw-bold"><i class="fas fa-save me-2"></i> LƯU SẢN PHẨM</button>
                            <a href="EVA_admin_product_manage.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>