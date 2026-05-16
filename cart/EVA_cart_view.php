<?php
session_start();
// 1. KẾT NỐI DB (Dùng đường dẫn tương đối)
include '../includes/EVA_system_db.php';

// --- XỬ LÝ THÊM VÀO GIỎ (action=them) ---
if (isset($_GET['action']) && $_GET['action'] == 'them' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Thêm hoặc tăng số lượng
    if (isset($_SESSION['cart'][$id])) {
        $current_val = $_SESSION['cart'][$id];
        // Fix lỗi nếu session cũ lưu dạng array
        if(is_array($current_val)) $current_val = 0; 
        
        $_SESSION['cart'][$id] = intval($current_val) + 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    // --- CHUYỂN HƯỚNG SAU KHI THÊM ---
    if(isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        // Trỏ về danh sách sản phẩm (đường dẫn mới)
        header("Location: ../product/EVA_product_list.php");
    }
    exit();
}

// --- XỬ LÝ CẬP NHẬT / XÓA (POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CẬP NHẬT SỐ LƯỢNG
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = intval($_POST['id']);
        $sl = intval($_POST['sl']);
        if ($sl > 0) {
            $_SESSION['cart'][$id] = $sl;
        } else {
            unset($_SESSION['cart'][$id]); // Nếu số lượng <= 0 thì xóa luôn
        }
    }
    // XÓA SẢN PHẨM
    if (isset($_POST['action']) && $_POST['action'] == 'remove') {
        $id = intval($_POST['id']);
        unset($_SESSION['cart'][$id]);
    }
}

// --- HIỂN THỊ GIỎ HÀNG ---
$cart_items = [];
$total = 0;
$ids = "";

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    
    if (!empty($ids)) {
        $result = $conn->query("SELECT * FROM sanpham WHERE id IN ($ids)");
        while ($row = $result->fetch_assoc()) {
            
            // FIX LỖI SESSION CŨ
            $raw_sl = $_SESSION['cart'][$row['id']];
            if (is_array($raw_sl)) {
                $sl = 1; 
                $_SESSION['cart'][$row['id']] = 1;
            } else {
                $sl = intval($raw_sl);
            }
            // ---------------------

            $row['sl_gio_hang'] = $sl;
            $row['thanh_tien'] = $row['gia'] * $sl;
            $total += $row['thanh_tien'];
            $cart_items[] = $row;
        }
    }
}

// --- TÍCH HỢP AI RECOMMENDATION CHO GIỎ HÀNG ---
$goi_y_modal = null;
if (!empty($ids)) {
    // Tìm các sản phẩm gợi ý dựa trên NHỮNG món đang có trong giỏ
    // Loại trừ các món đã có trong giỏ hàng bằng NOT IN ($ids)
    $sql_ai = "SELECT s.*, ai.score 
               FROM ai_recommendations ai 
               JOIN sanpham s ON ai.recommended_product_id = s.id 
               WHERE ai.product_id IN ($ids) 
               AND ai.recommended_product_id NOT IN ($ids) 
               ORDER BY ai.score DESC LIMIT 1"; 
               
    $rs_ai = $conn->query($sql_ai);
    if ($rs_ai && $rs_ai->num_rows > 0) {
        $goi_y_modal = $rs_ai->fetch_assoc();
    }
}

// Định nghĩa base_url để load ảnh
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/EVASHOP/';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - EVA SHOP</title>
    <?php include '../includes/EVA_system_header.php'; ?>
    <style>
        .table img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .qty-input { width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 4px; }
        .ai-recommendation-box { border: 2px dashed #ffc107; background-color: #fff9e6; border-radius: 10px; padding: 15px; margin-top: 20px; transition: 0.3s;}
        .ai-recommendation-box:hover {background-color: #fff3cd;}
    </style>
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <h3 class="text-center fw-bold mb-4 text-uppercase"><i class="fas fa-shopping-cart"></i> Giỏ Hàng Của Bạn</h3>

    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5 bg-white shadow-sm rounded">
            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="100" class="mb-3 opacity-50" alt="Empty Cart">
            <h5 class="text-muted">Giỏ hàng đang trống</h5>
            <a href="../product/EVA_product_list.php" class="btn btn-warning mt-3 fw-bold">TIẾP TỤC MUA SẮM</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?=$base_url . tim_hinh_anh($item['hinh_anh'])?>" class="border me-3" alt="<?=$item['ten_sanpham']?>">
                                            <div>
                                                <h6 class="mb-0 fw-bold">
                                                    <a href="../product/EVA_product_detail.php?id=<?=$item['id']?>" class="text-dark text-decoration-none">
                                                        <?=$item['ten_sanpham']?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted">Mã: <?=$item['ma_sp']?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?=number_format($item['gia'])?>₫</td>
                                    <td>
                                        <form method="POST" class="d-flex">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?=$item['id']?>">
                                            <input type="number" name="sl" value="<?=$item['sl_gio_hang']?>" min="1" class="qty-input form-control form-control-sm" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="fw-bold text-danger"><?=number_format($item['thanh_tien'])?>₫</td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="id" value="<?=$item['id']?>">
                                            <button class="btn btn-sm btn-outline-danger border-0" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 text-uppercase">Cộng Giỏ Hàng</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span class="fw-bold"><?=number_format($total)?>₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success fw-bold">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-4 fw-bold text-danger mb-4">
                            <span>TỔNG CỘNG:</span>
                            <span><?=number_format($total)?>₫</span>
                        </div>

                        <?php if (!empty($goi_y_modal)): 
                            $gia_km = $goi_y_modal['gia'] * 0.9; // Giá ưu đãi giảm 10%
                        ?>
                            <div class="ai-recommendation-box mb-4 shadow-sm">
                                <div class="text-center mb-2">
                                    <span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-gift me-1"></i> ƯU ĐÃI TỪ AI</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <img src="<?=$base_url . tim_hinh_anh($goi_y_modal['hinh_anh'])?>" class="rounded me-3 border" style="width: 70px; height: 70px; object-fit: cover;">
                                    <div>
                                        <h6 class="fw-bold mb-1 fs-6"><?=$goi_y_modal['ten_sanpham']?></h6>
                                        <div>
                                            <span class="text-danger fw-bold fs-6"><?=number_format($gia_km)?> ₫</span>
                                            <del class="text-muted small ms-1"><?=number_format($goi_y_modal['gia'])?> ₫</del>
                                        </div>
                                    </div>
                                </div>
                                <a href="EVA_cart_view.php?action=them&id=<?=$goi_y_modal['id']?>" class="btn btn-warning fw-bold w-100 btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i> Thêm Kèm Vào Giỏ
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-secondary text-center small mb-4" style="border: 1px dashed #ccc;">
                                <i class="fas fa-robot text-muted mb-1 fs-4"></i><br>
                                AI chưa tìm thấy quy luật mua kèm cho các sản phẩm này.
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['username'])): ?>
                            <a href="../order/EVA_order_checkout.php" class="btn btn-success w-100 py-3 fw-bold text-uppercase shadow-sm mb-2">
                                TIẾN HÀNH THANH TOÁN <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        <?php else: ?>
                            <a href="../auth/EVA_auth_login.php" class="btn btn-warning w-100 py-3 fw-bold shadow-sm mb-2">
                                ĐĂNG NHẬP ĐỂ THANH TOÁN
                            </a>
                        <?php endif; ?>
                        
                        <a href="../product/EVA_product_list.php" class="btn btn-outline-secondary w-100 fw-bold mt-2">
                            <i class="fas fa-arrow-left me-2"></i> Tiếp tục xem hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>