<?php
session_start();
include 'includes/EVA_system_db.php'; 

// 1. LẤY DỮ LIỆU TỪ DB
$banners = $conn->query("SELECT * FROM banner WHERE trang_thai=1 ORDER BY thu_tu");
$new_sp = $conn->query("SELECT s.*, t.ten_thuonghieu FROM sanpham s LEFT JOIN thuonghieu t ON s.thuonghieu_id=t.id WHERE s.trang_thai=1 ORDER BY s.id DESC LIMIT 4");
$hot_sp = $conn->query("SELECT s.*, t.ten_thuonghieu FROM sanpham s LEFT JOIN thuonghieu t ON s.thuonghieu_id=t.id WHERE s.trang_thai=1 ORDER BY RAND() LIMIT 4");

// 2. CẤU HÌNH MENU ICON
$quick_links = [
    [
        'name'  => 'Săn Sale',
        'img'   => 'https://cdn-icons-png.flaticon.com/512/726/726476.png', 
        'link'  => 'product/EVA_product_list.php?price=1',
        'onclick' => ''
    ],
    [
        'name'  => 'Freeship',
        'img'   => 'https://cdn-icons-png.flaticon.com/512/411/411763.png', 
        'link'  => 'product/EVA_product_list.php',
        'onclick' => ''
    ],
    [
        'name'  => 'Voucher',
        'img'   => 'https://cdn-icons-png.flaticon.com/512/2534/2534204.png', 
        'link'  => 'user/EVA_user_vouchers.php',
        'onclick' => ''
    ],
    [
        'name'  => 'Điểm Danh',
        'img'   => 'https://cdn-icons-png.flaticon.com/512/2693/2693507.png', 
        'link'  => 'javascript:void(0);',
        'onclick' => 'openCheckinModal()'
    ],
    [
        'name'  => 'Vòng Quay',
        'img'   => 'https://cdn-icons-png.flaticon.com/512/14540/14540324.png', 
        'link'  => 'game/EVA_game_wheel.php',
        'onclick' => ''
    ],
    [
        'name'  => 'Hàng Hiệu',
        'img'   => 'https://cdn-icons-png.flaticon.com/512/6556/6556073.png', 
        'link'  => 'product/EVA_product_list.php?thuonghieu=1',
        'onclick' => ''
    ]
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - EVA SHOP</title>
    <style>
        /* CSS BANNER */
        .banner-section { margin-top: 20px; }
        .main-carousel .carousel-item img { width: 100%; height: auto; min-height: 250px; max-height: 400px; object-fit: fill; border-radius: 4px; }
        .right-banner img { width: 100%; height: 145px; object-fit: cover; border-radius: 4px; margin-bottom: 10px; display: block; transition: transform 0.3s; }
        .right-banner img:hover { transform: scale(1.02); }

        /* CSS MENU ICON */
        .quick-link-item { text-decoration: none; color: #212529; display: flex; flex-direction: column; align-items: center; min-width: 85px; transition: transform 0.2s; cursor: pointer; }
        .quick-link-item:hover { transform: translateY(-5px); color: #ffc107; }
        .icon-circle { background: #fff; border-radius: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 8px; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; border: 1px solid #f0f0f0; }
        .icon-circle img { width: 35px; height: 35px; object-fit: contain; }

        .section-title { border-left: 5px solid #ffc107; padding-left: 10px; color: #212529; font-weight: 800; text-transform: uppercase; }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body style="background-color: #f5f5f5;">
    
    <?php include 'includes/EVA_system_header.php'; ?>

    <div class="container banner-section">
        <div class="row g-2"> 
            <div class="col-lg-8">
                <?php if($banners && $banners->num_rows > 0): ?>
                <div id="bn" class="carousel slide main-carousel shadow-sm" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php 
                        $banners->data_seek(0); $count = 0;
                        while($b = $banners->fetch_assoc()) {
                            echo '<button type="button" data-bs-target="#bn" data-bs-slide-to="'.$count.'" class="'.($count==0?'active':'').'"></button>';
                            $count++;
                        }
                        ?>
                    </div>
                    <div class="carousel-inner">
                        <?php 
                        $i = 0; $banners->data_seek(0);
                        while($b=$banners->fetch_assoc()): 
                            $img = tim_hinh_anh($b['hinh_anh']); 
                            $active = ($i++ == 0) ? 'active' : '';
                        ?>
                            <div class="carousel-item <?=$active?>" data-bs-interval="3000">
                                <img src="<?=$img?>" class="d-block w-100" alt="Banner">
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#bn" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bn" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4 right-banner d-none d-lg-block">
                <a href="#"><img src="images/banner4.png" alt="Banner Right 1"></a>
                <a href="#"><img src="images/banner6.png" alt="Banner Right 2"></a>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="bg-white p-3 rounded shadow-sm d-flex align-items-center justify-content-between" style="border: 2px solid #ee4d2d;">
            <div class="d-flex align-items-center gap-3">
                <h4 class="m-0 fw-bold fst-italic text-danger" style="font-family: 'Arial Black', sans-serif;">⚡ FLASH SALE TẾT</h4>
                <div class="d-flex gap-2 text-white fw-bold">
                    <span class="bg-dark px-2 rounded" id="h">00</span> :
                    <span class="bg-dark px-2 rounded" id="m">00</span> :
                    <span class="bg-dark px-2 rounded" id="s">00</span>
                </div>
            </div>
            <a href="product/EVA_product_list.php?price=1" class="text-danger fw-bold text-decoration-none">Xem tất cả ></a>
        </div>
    </div>

    <div class="container mt-3">
        <div class="bg-white p-3 shadow-sm rounded d-flex justify-content-around overflow-auto">
            <?php foreach($quick_links as $link): ?>
                <?php 
                    $onclick = isset($link['onclick']) ? 'onclick="'.$link['onclick'].'"' : '';
                ?>
                <a href="<?=$link['link']?>" class="quick-link-item" <?=$onclick?>>
                    <div class="icon-circle">
                        <img src="<?=$link['img']?>" alt="<?=$link['name']?>">
                    </div>
                    <small class="fw-bold text-nowrap" style="font-size: 13px;"><?=$link['name']?></small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container mt-4">
        <div class="bg-white p-3 border-bottom d-flex justify-content-between align-items-center rounded-top">
            <h5 class="section-title">SẢN PHẨM MỚI</h5>
            <a href="product/EVA_product_list.php" class="text-secondary small text-decoration-none fw-bold">Xem thêm ></a>
        </div>
        <div class="bg-white p-3 rounded-bottom shadow-sm">
            <div class="row">
                <?php while($row=$new_sp->fetch_assoc()) { include 'product/EVA_product_card.php'; } ?>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-5">
        <div class="bg-white p-3 border-bottom d-flex justify-content-between align-items-center rounded-top">
            <h5 class="section-title" style="border-color: #dc3545;">SẢN PHẨM HOT</h5>
            <a href="product/EVA_product_list.php" class="text-secondary small text-decoration-none fw-bold">Xem thêm ></a>
        </div>
        <div class="bg-white p-3 rounded-bottom shadow-sm">
            <div class="row">
                <?php while($row=$hot_sp->fetch_assoc()) { include 'product/EVA_product_card.php'; } ?>
            </div>
        </div>
    </div>

    <?php include 'includes/EVA_system_footer.php'; ?>
    <?php include 'includes/EVA_event_checkin.php'; ?>
    
    <script>
        // Hàm mở Popup Điểm danh
        function openCheckinModal() {
            var modal = document.getElementById('checkinModal');
            if(modal) {
                modal.style.display = 'flex';
            } else {
                console.error('Không tìm thấy modal điểm danh');
            }
        }

        // Script Đếm ngược Flash Sale
        function updateCountdown() {
            const now = new Date();
            // Đếm ngược đến 0h ngày mai
            const tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
            const diff = tomorrow - now;

            const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const m = Math.floor((diff / 1000 / 60) % 60);
            const s = Math.floor((diff / 1000) % 60);

            document.getElementById("h").innerText = h < 10 ? "0" + h : h;
            document.getElementById("m").innerText = m < 10 ? "0" + m : m;
            document.getElementById("s").innerText = s < 10 ? "0" + s : s;
        }
        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</body>
</html>