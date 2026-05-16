<?php
session_start();
// SỬA: Đường dẫn DB
require_once '../includes/EVA_system_db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { 
    header('Location: EVA_news_list.php'); 
    exit(); 
}

$stmt = $conn->prepare("SELECT * FROM tintuc WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$tin = $stmt->get_result()->fetch_assoc();

if (!$tin) die("Không tìm thấy bài viết");

function h($string) { return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); }

// Xử lý ảnh: Thêm ../ vì đang ở thư mục con
$hinh_anh = tim_hinh_anh($tin['hinh_anh']);
if(!empty($hinh_anh)) {
    $hinh_anh = "../" . $hinh_anh;
} else {
    $hinh_anh = "https://placehold.co/800x400?text=No+Image";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= h($tin['tieu_de']) ?></title>
    </head>
<body>
    <?php include '../includes/EVA_system_header.php'; ?>

    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="../index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="EVA_news_list.php">Tin tức</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= h($tin['tieu_de']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4 mb-5" style="max-width: 900px;">
        <h1 class="fw-bold mb-3 text-dark" style="line-height: 1.4;"><?= h($tin['tieu_de']) ?></h1>
        <div class="d-flex align-items-center text-muted mb-4 border-bottom pb-3">
            <div class="me-4"><i class="far fa-calendar-alt me-2"></i><?=date('d/m/Y', strtotime($tin['ngay_dang']))?></div>
            <div><i class="far fa-user me-2"></i>Đăng bởi: <strong><?= h($tin['nguoi_dang'] ?? 'Admin') ?></strong></div>
        </div>

        <div class="text-center mb-5">
            <img src="<?= $hinh_anh ?>" class="img-fluid rounded shadow-sm" style="max-height: 500px; width: 100%; object-fit: cover;">
        </div>

        <div class="content fs-5 lh-lg text-justify" style="color: #333;">
            <?= isset($tin['noi_dung_chi_tiet']) && $tin['noi_dung_chi_tiet'] !== '' 
                ? html_entity_decode($tin['noi_dung_chi_tiet']) 
                : nl2br(h($tin['noi_dung_tom_tat'] ?? 'Chưa có nội dung chi tiết.')) ?>
        </div>

        <div class="mt-5 pt-4 border-top">
            <h5 class="fw-bold mb-3">BÀI VIẾT KHÁC</h5>
            <a href="EVA_news_list.php" class="btn btn-outline-primary">← Xem thêm tin tức khác</a>
        </div>
    </div>

    <?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>