<?php
session_start();
// SỬA: Đường dẫn DB
require_once '../includes/EVA_system_db.php';

$stmt = $conn->prepare("SELECT * FROM tintuc ORDER BY ngay_dang DESC");
$stmt->execute();
$result = $stmt->get_result();

function h($string) { return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tin tức - EVA SHOP</title>
    </head>
<body>
    <?php include '../includes/EVA_system_header.php'; ?>
    
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="../index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Tin tức</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-4 mb-5">
        <h2 class="text-center mb-5 fw-bold text-uppercase text-primary border-bottom pb-3 d-inline-block" style="border-width: 3px !important;">🏸 Tin Tức Cầu Lông</h2>
        <div class="row">
            <?php if ($result->num_rows > 0): while($tin = $result->fetch_assoc()):
                // SỬA: Xử lý đường dẫn ảnh
                $img_news = tim_hinh_anh($tin['hinh_anh']);
                if (!empty($img_news)) {
                    $img_news = "../" . $img_news; // Thêm ../ để ra thư mục gốc
                } else {
                    $img_news = "https://placehold.co/600x400?text=Tin+Tuc";
                }
                
                $title = h($tin['tieu_de']);
                $summary = h(mb_substr($tin['noi_dung_tom_tat'] ?? '', 0, 120));
            ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-card">
                    <div class="row g-0 h-100">
                        <div class="col-md-5 overflow-hidden">
                            <a href="EVA_news_detail.php?id=<?= (int)$tin['id'] ?>">
                                <img src="<?=$img_news?>" class="img-fluid rounded-start h-100 w-100" style="object-fit:cover; min-height:200px;" alt="<?=$title?>">
                            </a>
                        </div>
                        <div class="col-md-7">
                            <div class="card-body d-flex flex-column h-100">
                                <h5 class="card-title fw-bold">
                                    <a href="EVA_news_detail.php?id=<?= (int)$tin['id'] ?>" class="text-decoration-none text-dark hover-yellow">
                                        <?=$title?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted small mb-2"><i class="far fa-clock"></i> <?=date('d/m/Y', strtotime($tin['ngay_dang']))?></p>
                                <p class="card-text text-secondary"><?=$summary?>...</p>
                                <div class="mt-auto">
                                    <a href="EVA_news_detail.php?id=<?= (int)$tin['id'] ?>" class="btn btn-sm btn-outline-warning fw-bold text-dark">Đọc tiếp →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="100" class="mb-3 opacity-50">
                    <h4 class="text-muted">Chưa có tin tức nào!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/EVA_system_footer.php'; ?>
    
    <style>
        .hover-card { transition: transform 0.3s; }
        .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .hover-yellow:hover { color: #ffc107 !important; }
    </style>
</body>
</html>