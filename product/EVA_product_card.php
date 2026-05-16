<?php
// Kiểm tra nếu chưa có $base_url thì tự tạo (phòng hờ)
if(!isset($base_url)) {
    $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/EVASHOP/';
}
?>

<div class="col-md-3 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm border border-light bg-white">
        
        <div class="product-img-container">
            <img src="<?=$base_url . tim_hinh_anh($row['hinh_anh'])?>" alt="<?=$row['ten_sanpham']?>">
        </div>

        <div class="card-body d-flex flex-column p-3">
            <h6 class="card-title text-truncate mb-2" title="<?=$row['ten_sanpham']?>">
                <a href="<?=$base_url?>product/EVA_product_detail.php?id=<?=$row['id']?>" class="text-dark fw-bold stretched-link text-decoration-none">
                    <?=$row['ten_sanpham']?>
                </a>
            </h6>

            <?php if(isset($row['ten_thuonghieu'])): ?>
                <div class="mb-2">
                    <span class="badge bg-light text-dark border"><?=$row['ten_thuonghieu']?></span>
                </div>
            <?php endif; ?>

            <p class="text-danger fw-bold fs-5 mb-3"><?=number_format($row['gia'])?> ₫</p>

            <div class="mt-auto d-grid gap-2">
                <a href="<?=$base_url?>product/EVA_product_detail.php?id=<?=$row['id']?>" class="btn btn-outline-primary btn-sm fw-bold" style="position:relative; z-index:2">
                    Xem
                </a>
                
                <a href="<?=$base_url?>cart/EVA_cart_view.php?action=them&id=<?=$row['id']?>" class="btn btn-success btn-sm fw-bold" style="position:relative; z-index:2">
                    + Giỏ
                </a>
            </div>
        </div>
    </div>
</div>