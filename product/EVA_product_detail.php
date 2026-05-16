<?php
session_start();
// 1. GỌI DB (Dùng đường dẫn tương đối ../)
include '../includes/EVA_system_db.php';

// Check ID
if (!isset($_GET['id'])) { 
    header('Location: ../index.php'); 
    exit(); 
}
$id = intval($_GET['id']);

// Lấy sản phẩm
$sp = $conn->query("SELECT s.*, d.ten_danhmuc, t.ten_thuonghieu 
                    FROM sanpham s 
                    LEFT JOIN danhmuc d ON s.danhmuc_id=d.id 
                    LEFT JOIN thuonghieu t ON s.thuonghieu_id=t.id 
                    WHERE s.id=$id")->fetch_assoc();

if (!$sp) die("Sản phẩm không tồn tại");

// --- 1. TÍNH ĐIỂM TRUNG BÌNH ---
$sql_rating = "SELECT AVG(so_sao) as trung_binh, COUNT(*) as tong_luot FROM danhgia WHERE sanpham_id = $id";
$rs_rating = mysqli_query($conn, $sql_rating);
$rating_data = mysqli_fetch_assoc($rs_rating);
$diem_tb = round($rating_data['trung_binh'], 1); 
$tong_luot = $rating_data['tong_luot'];

// --- 2. TÍNH ĐÃ BÁN ---
$sql_sold = "SELECT SUM(so_luong) as da_ban FROM chitietdonhang WHERE sanpham_id = $id";
$rs_sold = mysqli_query($conn, $sql_sold);
$sold_data = mysqli_fetch_assoc($rs_sold);
$da_ban = intval($sold_data['da_ban']);

// --- 3. XỬ LÝ ĐÁNH GIÁ ---
if (isset($_POST['btnGuiDanhGia'])) {
    if (isset($_SESSION['username'])) {
        $so_sao = intval($_POST['rate']);
        $noi_dung = mysqli_real_escape_string($conn, $_POST['noi_dung']);
        // SỬA: Lấy user_id thay vì username để lưu vào bảng danhgia mới
        $user_id = $_SESSION['user_id']; 
        
        if ($so_sao > 0) {
            // SỬA: Insert theo user_id
            $sql_dg = "INSERT INTO danhgia (sanpham_id, user_id, so_sao, noi_dung) 
                       VALUES ($id, $user_id, $so_sao, '$noi_dung')";
            
            // Dùng try-catch để bắt lỗi trùng lặp (UNIQUE KEY)
            try {
                if(mysqli_query($conn, $sql_dg)) {
                    echo "<script>alert('Cảm ơn bạn đã đánh giá!'); window.location.href=window.location.href;</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Bạn đã đánh giá sản phẩm này rồi!');</script>";
            }
        } else {
            echo "<script>alert('Vui lòng chọn số sao!');</script>";
        }
    } else {
        echo "<script>alert('Bạn cần đăng nhập!'); window.location='../auth/EVA_auth_login.php';</script>";
    }
}

// Xử lý SIZE
$sizes = [];
$thong_so = $sp['thong_so'];
if (strpos($thong_so, 'Size') !== false && strpos($thong_so, '-') !== false) {
    preg_match('/(\d+)\s*-\s*(\d+)/', $thong_so, $m);
    if(isset($m[1]) && isset($m[2])) {
        for($i=$m[1]; $i<=$m[2]; $i++) $sizes[] = $i;
    }
} else {
    $sizes = array_map('trim', explode(',', str_replace('/', ',', $thong_so)));
}

// Ảnh & Sản phẩm liên quan
$imgs = $conn->query("SELECT * FROM thuvienanh WHERE sanpham_id=$id");
$rel = $conn->query("SELECT * FROM sanpham WHERE danhmuc_id={$sp['danhmuc_id']} AND id!=$id LIMIT 4");

// BASE URL CHO ẢNH
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/EVASHOP/';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?=$sp['ten_sanpham']?></title>
    <?php include '../includes/EVA_system_header.php'; ?>
    
    <style>
        .size-radio:checked + .size-label {
            background-color: #ffc107; border-color: #ffc107; color: #000; font-weight: bold;
        }
        .size-label { cursor: pointer; min-width: 40px; text-align: center; }
        
        /* CSS ĐÁNH GIÁ (GIỮ NGUYÊN) */
        .rating-overview { display: flex; align-items: center; margin-bottom: 20px; font-size: 16px; color: #333; background: #fff; padding: 10px 0; }
        .rating-score { color: #ee4d2d; border-bottom: 1px solid #ee4d2d; font-size: 18px; font-weight: bold; margin-right: 5px; }
        .rating-stars-static { color: #ee4d2d; margin-right: 15px; font-size: 14px; }
        .divider { color: #ddd; margin: 0 15px; height: 20px; border-left: 1px solid #ddd; }
        .metric-value { font-size: 18px; border-bottom: 1px solid #555; margin-right: 5px; font-weight: 500; }
        .metric-label { color: #767676; font-size: 14px; }
        
        .star-rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
        .star-rating-input input { display: none; }
        .star-rating-input label { font-size: 30px; color: #ccc; cursor: pointer; transition: color 0.2s; padding: 0 2px; }
        .star-rating-input input:checked ~ label,
        .star-rating-input label:hover,
        .star-rating-input label:hover ~ label { color: #ffc107; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card border-0 p-2 shadow-sm">
                    <div style="height: 400px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img id="mainImage" src="<?=$base_url . tim_hinh_anh($sp['hinh_anh'])?>" style="max-height: 100%; max-width: 100%; width: auto; height: auto; object-fit: contain;">
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-2 gap-2 flex-wrap">
                    <img src="<?=$base_url . tim_hinh_anh($sp['hinh_anh'])?>" class="img-thumbnail" onclick="doiAnh(this)" style="width:60px; height:60px; object-fit:cover; cursor:pointer;">
                    <?php while($img=$imgs->fetch_assoc()): $src=tim_hinh_anh($img['ten_file_anh']); if($src): ?>
                        <img src="<?=$base_url . $src?>" class="img-thumbnail" onclick="doiAnh(this)" style="width:60px; height:60px; object-fit:cover; cursor:pointer;">
                    <?php endif; endwhile; ?>
                </div>
            </div>

            <div class="col-md-7">
                <h2 class="fw-bold"><?=$sp['ten_sanpham']?></h2>
                
                <div class="rating-overview">
                    <div class="d-flex align-items-center">
                        <span class="rating-score"><?=$diem_tb?></span>
                        <div class="rating-stars-static">
                            <?php 
                            for($i=1; $i<=5; $i++) {
                                if($i <= $diem_tb) echo '<i class="fas fa-star"></i>';
                                elseif($i - 0.5 <= $diem_tb) echo '<i class="fas fa-star-half-alt"></i>';
                                else echo '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-baseline">
                        <span class="metric-value"><?=$tong_luot?></span> <span class="metric-label">Đánh Giá</span>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-baseline">
                        <span class="metric-value"><?=$da_ban?></span> <span class="metric-label">Đã Bán</span>
                    </div>
                </div>

                <h3 class="text-danger fw-bold mb-4 mt-3"><?=number_format($sp['gia'])?> ₫</h3>
                
                <form action="../cart/EVA_cart_view.php" method="GET" id="formMua">
                    <input type="hidden" name="action" value="them">
                    <input type="hidden" name="id" value="<?=$sp['id']?>">

                    <div class="mb-4">
                        <h6 class="fw-bold">Chọn kích thước / Phiên bản:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php if(!empty($sizes)): foreach($sizes as $k => $sz): ?>
                                <input type="radio" class="btn-check size-radio" name="size" id="sz<?=$k?>" value="<?=$sz?>" <?=$k==0?'checked':''?>>
                                <label class="btn btn-outline-secondary size-label" for="sz<?=$k?>"><?=$sz?></label>
                            <?php endforeach; else: ?>
                                <span class="text-muted">Tiêu chuẩn</span>
                                <input type="hidden" name="size" value="Tiêu chuẩn">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="button" onclick="muaNgay()" class="btn btn-danger btn-lg flex-grow-1 fw-bold text-uppercase">
                            <i class="fas fa-shopping-bag me-2"></i> Mua Ngay
                        </button>
                        <button type="submit" class="btn btn-outline-primary btn-lg flex-grow-1 fw-bold text-uppercase">
                            <i class="fas fa-cart-plus me-2"></i> Thêm Giỏ
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 alert alert-light border">
                    <ul class="mb-0 list-unstyled small">
                        <li>📦 <strong>Tình trạng:</strong> <?=($sp['so_luong']>0?'<span class="text-success fw-bold">Còn hàng</span>':'<span class="text-danger fw-bold">Hết hàng</span>')?></li>
                        <li>🛡️ Bảo hành chính hãng 12 tháng.</li>
                        <li>🔄 Đổi trả trong 7 ngày nếu lỗi nhà sản xuất.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h4>Mô tả chi tiết</h4>
                <div class="border p-4 rounded bg-white" style="white-space: pre-line;"><?=nl2br($sp['mo_ta'])?></div>
            </div>
        </div>

        <?php 
        // Query lấy đánh giá + tên user từ bảng taikhoan
        $qry_dg = mysqli_query($conn, "SELECT d.*, t.hoten FROM danhgia d 
                                       JOIN taikhoan t ON d.user_id = t.id 
                                       WHERE sanpham_id = $id ORDER BY d.id DESC");
        ?>
        <div class="card shadow-sm mt-5 mb-5 border-0">
            <div class="card-header bg-white border-bottom-0">
                <h4 class="fw-bold text-uppercase"><i class="fas fa-star text-warning"></i> Đánh giá sản phẩm</h4>
            </div>
            <div class="card-body">
                
                <?php
                $allow_review = false;
                $msg_review = "Vui lòng đăng nhập để đánh giá.";
                
                if(isset($_SESSION['username'])) {
                    $u_check = $_SESSION['username'];
                    // Query check đã mua & hoàn thành
                    $sql_check = "SELECT d.id FROM donhang d 
                                  JOIN chitietdonhang ct ON d.id = ct.donhang_id 
                                  WHERE d.username = '$u_check' 
                                  AND ct.sanpham_id = $id 
                                  AND d.trang_thai = 'Hoàn thành'"; 
                    $rs_check = $conn->query($sql_check);
                    
                    if($rs_check->num_rows > 0) {
                        $allow_review = true;
                    } else {
                        $msg_review = "Bạn cần mua sản phẩm này và đơn hàng 'Hoàn thành' mới được đánh giá.";
                    }
                }
                ?>

                <?php if($allow_review): ?>
                    <form method="POST" class="mb-5 p-4 bg-light rounded">
                        <h6 class="fw-bold">Viết đánh giá của bạn:</h6>
                        <div class="star-rating-input mb-3">
                            <input type="radio" id="star5" name="rate" value="5" required /><label for="star5" title="Tuyệt vời"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4" name="rate" value="4" /><label for="star4" title="Tốt"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3" name="rate" value="3" /><label for="star3" title="Bình thường"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2" name="rate" value="2" /><label for="star2" title="Tệ"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star1" name="rate" value="1" /><label for="star1" title="Rất tệ"><i class="fas fa-star"></i></label>
                        </div>
                        <div class="mb-3">
                            <textarea name="noi_dung" class="form-control" rows="3" placeholder="Sản phẩm dùng thế nào?..." required></textarea>
                        </div>
                        <button type="submit" name="btnGuiDanhGia" class="btn btn-warning fw-bold text-dark">Gửi đánh giá</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-secondary text-center py-4">
                        <i class="fas fa-lock fa-2x mb-2 text-muted"></i><br>
                        <?=$msg_review?>
                    </div>
                <?php endif; ?>

                <div class="review-list">
                    <?php if(mysqli_num_rows($qry_dg) > 0): ?>
                        <?php while($dg = mysqli_fetch_assoc($qry_dg)): ?>
                        <div class="d-flex mb-4 border-bottom pb-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;">
                                    <?=strtoupper(substr($dg['hoten'], 0, 1))?>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold"><?=$dg['hoten']?></h6>
                                    <small class="text-muted"><?=date('d/m/Y', strtotime($dg['ngay_tao']))?></small>
                                </div>
                                <div class="text-warning mb-2 small">
                                    <?php for($i=1; $i<=5; $i++) echo ($i<=$dg['so_sao'])?'<i class="fas fa-star"></i>':'<i class="far fa-star text-muted"></i>'; ?>
                                </div>
                                <p class="mb-0 text-secondary"><?=$dg['noi_dung']?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted py-3">Chưa có đánh giá nào.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h4>Sản phẩm tương tự</h4>
                <div class="row mt-3">
                    <?php 
                    while($row=$rel->fetch_assoc()) {
                        include 'EVA_product_card.php'; // Include file cùng thư mục
                    } 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function doiAnh(el){ document.getElementById('mainImage').src = el.src; }
        
    function muaNgay() {
        var form = document.getElementById('formMua');
        // SỬA LẠI: Trỏ về đúng file EVA_order_checkout.php trong thư mục order
        form.action = '../order/EVA_order_checkout.php'; 
        form.method = 'GET'; 
        form.submit();
    }   
    </script>
    
    <?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>