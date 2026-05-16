<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}
include 'EVA_admin_db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($conn, "SELECT * FROM sanpham WHERE id = $id");
$sp = mysqli_fetch_assoc($query);

if (!$sp) die("Sản phẩm không tồn tại!");

$error = "";
$success = "";

// --- XỬ LÝ CẬP NHẬT ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten = $_POST['ten_sanpham'];
    $gia = floatval($_POST['gia']);
    $sl = intval($_POST['so_luong']);
    $thong_so = $_POST['thong_so'];
    $danhmuc = intval($_POST['danhmuc_id']);
    $thuonghieu = intval($_POST['thuonghieu_id']);
    
    // [QUAN TRỌNG] Dùng hàm này để tránh lỗi SQL khi lưu mã HTML từ CKEditor
    $mota = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    
    $hinh_anh = $sp['hinh_anh']; // Giữ ảnh cũ

    // Nếu chọn ảnh mới
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['name'] != "") {
        $target_dir = "../images/";
        $file_name = time() . "_" . basename($_FILES["hinh_anh"]["name"]);
        if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_dir . $file_name)) {
            $hinh_anh = $file_name;
        }
    }

    $sql = "UPDATE sanpham SET 
            ten_sanpham='$ten', gia=$gia, so_luong=$sl, thong_so='$thong_so', 
            danhmuc_id=$danhmuc, thuonghieu_id=$thuonghieu, hinh_anh='$hinh_anh', mo_ta='$mota' 
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        $success = "Cập nhật thành công!";
        // Cập nhật lại dữ liệu hiển thị sau khi lưu
        $sp['ten_sanpham'] = $ten;
        $sp['gia'] = $gia;
        $sp['so_luong'] = $sl;
        $sp['thong_so'] = $thong_so;
        $sp['danhmuc_id'] = $danhmuc;
        $sp['thuonghieu_id'] = $thuonghieu;
        $sp['hinh_anh'] = $hinh_anh;
        $sp['mo_ta'] = $_POST['mo_ta']; // Gán lại để hiển thị đúng trong editor
    } else {
        $error = "Lỗi SQL: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="../ckeditor/ckeditor.js"></script>
</head>
<body class="bg-light">

<?php include 'EVA_admin_menu.php'; ?>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="fas fa-edit me-2"></i> CẬP NHẬT SẢN PHẨM: #<?=$id?>
                </div>
                <div class="card-body">
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên sản phẩm</label>
                                <input type="text" name="ten_sanpham" class="form-control" value="<?=$sp['ten_sanpham']?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Giá bán</label>
                                <input type="number" name="gia" class="form-control" value="<?=$sp['gia']?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Số lượng</label>
                                <input type="number" name="so_luong" class="form-control" value="<?=$sp['so_luong']?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Danh mục</label>
                                <select name="danhmuc_id" class="form-select">
                                    <?php 
                                    $dm = mysqli_query($conn, "SELECT * FROM danhmuc");
                                    while($d = mysqli_fetch_assoc($dm)) {
                                        $selected = ($d['id'] == $sp['danhmuc_id']) ? 'selected' : '';
                                        echo "<option value='{$d['id']}' $selected>{$d['ten_danhmuc']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Thương hiệu</label>
                                <select name="thuonghieu_id" class="form-select">
                                    <?php 
                                    $th = mysqli_query($conn, "SELECT * FROM thuonghieu");
                                    while($t = mysqli_fetch_assoc($th)) {
                                        $selected = ($t['id'] == $sp['thuonghieu_id']) ? 'selected' : '';
                                        echo "<option value='{$t['id']}' $selected>{$t['ten_thuonghieu']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Thông số</label>
                                <input type="text" name="thong_so" class="form-control" value="<?=$sp['thong_so']?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh hiện tại</label><br>
                            <img src="../images/<?=$sp['hinh_anh']?>" width="100" class="border rounded mb-2">
                            <input type="file" name="hinh_anh" class="form-control">
                            <div class="form-text">Bỏ qua nếu không muốn thay đổi ảnh.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="mo_ta" id="editor_mota" class="form-control" rows="5"><?=$sp['mo_ta']?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning fw-bold"><i class="fas fa-save me-2"></i> CẬP NHẬT</button>
                            <a href="EVA_admin_product_manage.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('editor_mota', {
            // Cấu hình đường dẫn CKFinder lùi ra 1 cấp thư mục (../)
            filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?type=Images',
            filebrowserFlashBrowseUrl: '../ckfinder/ckfinder.html?type=Flash',
            filebrowserUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
    } else {
        alert("Lỗi: Không tìm thấy CKEditor! Hãy kiểm tra lại folder '../ckeditor/'");
    }
</script>

</body>
</html>