<?php
session_start();
// 1. GỌI DB
include '../includes/EVA_system_db.php';

// --- CẤU HÌNH PHÂN TRANG ---
$limit = 8; // Số sản phẩm mỗi trang
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// XÂY DỰNG QUERY CƠ BẢN (KHÔNG LIMIT ĐỂ ĐẾM TỔNG)
$sql_base = "SELECT s.*, t.ten_thuonghieu FROM sanpham s 
             LEFT JOIN thuonghieu t ON s.thuonghieu_id = t.id 
             WHERE s.trang_thai = 1";

// 2. BẮT CÁC BỘ LỌC (Lưu lại tham số để nối vào link phân trang)
$params = []; // Mảng chứa tham số GET để build link

// Lọc Keyword
$keyword = '';
if(isset($_GET['keyword']) && $_GET['keyword']!=''){
    $keyword = $conn->real_escape_string($_GET['keyword']);
    $sql_base .= " AND s.ten_sanpham LIKE '%$keyword%'";
    $params['keyword'] = $keyword;
}

// Lọc Danh mục
$dm_name = "Tất cả sản phẩm";
if (isset($_GET['danhmuc']) && $_GET['danhmuc'] != '') {
    $dm_id = intval($_GET['danhmuc']);
    $sql_base .= " AND s.danhmuc_id = $dm_id";
    $res_dm = $conn->query("SELECT ten_danhmuc FROM danhmuc WHERE id=$dm_id");
    if($res_dm->num_rows > 0) $dm_name = $res_dm->fetch_assoc()['ten_danhmuc'];
    $params['danhmuc'] = $dm_id;
}

// Lọc Thương hiệu
if (isset($_GET['thuonghieu']) && $_GET['thuonghieu'] != '') {
    $th_id = intval($_GET['thuonghieu']);
    $sql_base .= " AND s.thuonghieu_id = $th_id";
    $params['thuonghieu'] = $th_id;
}

// Lọc Giá
$price_param = isset($_GET['price']) ? $_GET['price'] : (isset($_GET['gia']) ? $_GET['gia'] : '');
if ($price_param != '') {
    $params['price'] = $price_param;
    switch ($price_param) {
        case '1': case 'duoi1tr': $sql_base .= " AND s.gia < 1000000"; break;
        case '2': case '1den3tr': $sql_base .= " AND s.gia BETWEEN 1000000 AND 3000000"; break;
        case '3': $sql_base .= " AND s.gia BETWEEN 1000000 AND 2000000"; break;
        case '4': case 'tren3tr': $sql_base .= " AND s.gia > 2000000"; break;
    }
}

// 3. THỰC HIỆN ĐẾM TỔNG SỐ BẢN GHI (Cho phân trang)
$res_count = $conn->query($sql_base);
$total_records = $res_count->num_rows;
$total_pages = ceil($total_records / $limit);

// 4. QUERY LẤY DỮ LIỆU CÓ LIMIT
$sql_final = $sql_base . " ORDER BY s.id DESC LIMIT $offset, $limit";
$result = $conn->query($sql_final);

// Hàm tạo Link phân trang giữ nguyên bộ lọc
function buildUrl($page, $params) {
    $params['page'] = $page;
    return '?' . http_build_query($params);
}

// Lấy danh sách Sidebar
$ds_thuonghieu = $conn->query("SELECT * FROM thuonghieu");
$ds_danhmuc = $conn->query("SELECT * FROM danhmuc");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm - EVA SHOP</title>
    <?php include '../includes/EVA_system_header.php'; ?>
    
    <style>
        .filter-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #dee2e6; }
        .filter-title { font-weight: bold; margin-bottom: 10px; text-transform: uppercase; color: #333; border-bottom: 2px solid #ffc107; padding-bottom: 5px; display: inline-block; }
        .filter-list { list-style: none; padding: 0; margin: 0; }
        .filter-item { margin-bottom: 8px; }
        .filter-item label { cursor: pointer; width: 100%; }
        .filter-item input { margin-right: 8px; accent-color: #ffc107; }
        .filter-item:hover { color: #d39e00; }
        
        .breadcrumb-container { background: #eee; padding: 10px 0; margin-bottom: 30px; }
        
        /* Pagination Style */
        .pagination .page-link { color: #333; border: 1px solid #dee2e6; margin: 0 3px; border-radius: 5px; }
        .pagination .page-link:hover { background-color: #ffc107; color: #000; border-color: #ffc107; }
        .pagination .active .page-link { background-color: #ffc107; border-color: #ffc107; color: #000; font-weight: bold; }
    </style>
</head>
<body>

<div class="breadcrumb-container">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none text-dark">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$dm_name?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <form action="" method="GET">
                <input type="hidden" name="keyword" value="<?=htmlspecialchars($keyword)?>">
                
                <div class="filter-box">
                    <div class="filter-title">DANH MỤC</div>
                    <ul class="filter-list">
                        <li class="filter-item">
                            <label><input type="radio" name="danhmuc" value="" onchange="this.form.submit()" <?=!isset($_GET['danhmuc'])?'checked':''?>> Tất cả</label>
                        </li>
                        <?php 
                        $ds_danhmuc->data_seek(0); 
                        while($dm = $ds_danhmuc->fetch_assoc()): 
                        ?>
                        <li class="filter-item">
                            <label><input type="radio" name="danhmuc" value="<?=$dm['id']?>" onchange="this.form.submit()" <?=(isset($_GET['danhmuc'])&&$_GET['danhmuc']==$dm['id'])?'checked':''?>> <?=$dm['ten_danhmuc']?></label>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <div class="filter-box">
                    <div class="filter-title">THƯƠNG HIỆU</div>
                    <ul class="filter-list">
                        <li class="filter-item">
                            <label><input type="radio" name="thuonghieu" value="" onchange="this.form.submit()" <?=!isset($_GET['thuonghieu'])?'checked':''?>> Tất cả</label>
                        </li>
                        <?php 
                        $ds_thuonghieu->data_seek(0);
                        while($th = $ds_thuonghieu->fetch_assoc()): 
                        ?>
                        <li class="filter-item">
                            <label><input type="radio" name="thuonghieu" value="<?=$th['id']?>" onchange="this.form.submit()" <?=(isset($_GET['thuonghieu'])&&$_GET['thuonghieu']==$th['id'])?'checked':''?>> <?=$th['ten_thuonghieu']?></label>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <div class="filter-box">
                    <div class="filter-title">MỨC GIÁ</div>
                    <ul class="filter-list">
                        <li class="filter-item"><label><input type="radio" name="price" value="" onchange="this.form.submit()" <?=$price_param==''?'checked':''?>> Tất cả</label></li>
                        <li class="filter-item"><label><input type="radio" name="price" value="1" onchange="this.form.submit()" <?=$price_param=='1'?'checked':''?>> Dưới 1 triệu</label></li>
                        <li class="filter-item"><label><input type="radio" name="price" value="2" onchange="this.form.submit()" <?=$price_param=='2'?'checked':''?>> 1 - 3 triệu</label></li>
                        <li class="filter-item"><label><input type="radio" name="price" value="4" onchange="this.form.submit()" <?=$price_param=='4'?'checked':''?>> Trên 3 triệu</label></li>
                    </ul>
                </div>

                <a href="EVA_product_list.php" class="btn btn-outline-danger w-100 mt-2">Xóa bộ lọc</a>
            </form>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h4 class="fw-bold text-uppercase m-0">SẢN PHẨM</h4>
                <span class="text-muted">Tìm thấy <b><?=$total_records?></b> kết quả</span>
            </div>
            
            <div class="row">
                <?php if($result->num_rows > 0): ?>
                    <?php 
                    while($row = $result->fetch_assoc()) {
                        include 'EVA_product_card.php'; 
                    } 
                    ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="opacity-50 mb-3">
                        <h4 class="text-muted">Không tìm thấy sản phẩm nào!</h4>
                        <a href="EVA_product_list.php" class="btn btn-warning mt-2">Xem tất cả</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?=buildUrl($page-1, $params)?>"><i class="fas fa-chevron-left"></i></a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?=$page==$i?'active':''?>">
                            <a class="page-link" href="<?=buildUrl($i, $params)?>"><?=$i?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="<?=buildUrl($page+1, $params)?>"><i class="fas fa-chevron-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>