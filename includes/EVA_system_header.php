<?php
// 1. GỌI KẾT NỐI DB
if (!isset($conn)) {
    include_once __DIR__ . '/EVA_system_db.php';
}

// 2. CHECK COOKIE
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['username']) && isset($_COOKIE['user_login'])) {
    $user_cookie = mysqli_real_escape_string($conn, $_COOKIE['user_login']);
    $sql_check = "SELECT * FROM taikhoan WHERE username = '$user_cookie'";
    $rs_check = mysqli_query($conn, $sql_check);
    if (mysqli_num_rows($rs_check) > 0) {
        $row = mysqli_fetch_assoc($rs_check);
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id']; 
        $_SESSION['hoten'] = $row['hoten'];
        $_SESSION['role'] = $row['role'];
        if($row['role'] == 1) {
            $_SESSION['admin_user'] = $row['username'];
            $_SESSION['admin_name'] = $row['hoten'];
        }
    }
}

// 3. MENU
if (isset($conn)) $rs_menu = $conn->query("SELECT * FROM danhmuc");

// 4. BASE URL (Quan trọng: Giúp link không bị lỗi khi vào thư mục con)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . '/EVASHOP/'; 
$cur_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css?v=<?=time()?>"> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
    /* MÀU SẮC EVA SHOP */
    :root { --eva-dark: #212529; --eva-yellow: #ffc107; --eva-yellow-dark: #e0a800; }

    /* Navbar */
    .eva-header {
        background-color: var(--eva-dark);
        border-bottom: 3px solid var(--eva-yellow);
        padding-bottom: 10px;
        position: sticky; top: 0; z-index: 9999; 
        box-shadow: 0 5px 20px rgba(0,0,0,0.5); 
    }

    /* Logo Text */
    .brand-text { font-size: 1.5rem; font-weight: 800; color: var(--eva-yellow) !important; text-transform: uppercase; letter-spacing: 1px; }

    /* Search Box */
    .shopee-layout-search-box { background: white; border-radius: 2px; padding: 3px; display: flex; width: 100%; max-width: 700px; }
    .search-input { border: none; outline: none; flex-grow: 1; padding: 5px 15px; font-size: 14px; }
    .search-btn { background: var(--eva-yellow); color: black; border: none; padding: 5px 25px; border-radius: 2px; font-weight: bold; transition: 0.2s; }
    .search-btn:hover { background: var(--eva-yellow-dark); }

    .nav-link { color: rgba(255,255,255,0.8) !important; font-size: 14px; text-transform: uppercase; font-weight: 500; }
    .nav-link:hover, .nav-link.active { color: var(--eva-yellow) !important; font-weight: bold; }

    .cart-icon-wrap { position: relative; color: var(--eva-yellow); font-size: 26px; }
    .cart-badge { position: absolute; top: -8px; right: -12px; background: white; color: black; font-size: 11px; font-weight: bold; border-radius: 10px; padding: 2px 6px; border: 2px solid var(--eva-yellow); }

    .user-dropdown .dropdown-toggle { color: white !important; display: flex; align-items: center; gap: 5px; }
    
    /* Hiệu ứng hoa rơi */
    .tet-flower {
        position: fixed; top: -10px; z-index: 9999; user-select: none; pointer-events: none; opacity: 0.8;
        width: 12px; height: 12px; background: #ffeb3b; border-radius: 50% 0 50% 50%; transform: rotate(45deg);
        box-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }
</style>

<header class="eva-header">
    <div class="container d-flex justify-content-end py-1">
        <a href="<?php echo $base_url; ?>news/EVA_news_list.php" class="text-white text-decoration-none small mx-2 hover-yellow">Tin tức</a>
        <span class="text-secondary small">|</span>
        <a href="<?php echo $base_url; ?>page/EVA_page_contact.php" class="text-white text-decoration-none small mx-2 hover-yellow">Hỗ trợ</a>
        
        <?php if(isset($_SESSION['username'])): ?>
            <div class="dropdown user-dropdown ms-3">
                <a href="#" class="text-white text-decoration-none dropdown-toggle small fw-bold" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fs-5 me-1 text-warning"></i> <?=$_SESSION['username']?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>user/EVA_user_profile.php">Tài khoản</a></li>
                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>user/EVA_user_orders.php">Đơn mua</a></li>
                    <li><a class="dropdown-item" href="<?php echo $base_url; ?>user/EVA_user_vouchers.php">Kho Voucher</a></li>
                    <?php if($_SESSION['role']==1) echo '<li><a class="dropdown-item text-warning" href="'.$base_url.'admin/EVA_admin_dashboard.php">Trang Quản Trị</a></li>'; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?php echo $base_url; ?>auth/EVA_auth_logout.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php else: ?>
            <span class="text-secondary small mx-2">|</span>
            <a href="<?php echo $base_url; ?>auth/EVA_auth_login.php" class="text-warning text-decoration-none small fw-bold">Đăng nhập</a>
        <?php endif; ?>
    </div>

    <div class="container pt-2 pb-3">
        <div class="row align-items-center">
            <div class="col-lg-2 col-md-3 mb-2 mb-md-0">
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $base_url; ?>index.php">
                    <img src="<?php echo $base_url; ?>images/logo.png" alt="Logo" style="height: 45px;">
                    <div class="position-relative">
                        <span class="brand-text">EVA SHOP</span>
                        
                        <img src="<?php echo $base_url; ?>images/dao.png" 
                             style="position: absolute; top: -15px; right: -25px; width: 30px; transform: rotate(15deg);" 
                             alt="Tet Icon">
                    </div>
                </a>
            </div>

            <div class="col-lg-8 col-md-7 mb-2 mb-md-0 d-flex justify-content-center">
                <form class="shopee-layout-search-box shadow-sm" action="<?php echo $base_url; ?>product/EVA_product_list.php">
                    <input type="text" class="search-input" name="keyword" placeholder="Tìm kiếm vợt cầu lông, giày...">
                    <button class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="col-lg-2 col-md-2 text-center text-md-end">
                <a href="<?php echo $base_url; ?>cart/EVA_cart_view.php" class="cart-icon-wrap text-decoration-none">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge"><?=isset($_SESSION['cart'])?count($_SESSION['cart']):0?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="container d-none d-lg-block border-top border-secondary pt-1">
        <ul class="nav justify-content-center">
            <li class="nav-item"><a class="nav-link <?=($cur_page=='index.php')?'active':''?>" href="<?php echo $base_url; ?>index.php">Trang chủ</a></li>
            <li class="nav-item"><a class="nav-link <?=($cur_page=='EVA_product_list.php')?'active':''?>" href="<?php echo $base_url; ?>product/EVA_product_list.php">Tất cả sản phẩm</a></li>
            
            <?php 
            if(isset($rs_menu)) { 
                $rs_menu->data_seek(0); 
                while($m=$rs_menu->fetch_assoc()) {
                    echo '<li class="nav-item"><a class="nav-link" href="'.$base_url.'product/EVA_product_list.php?danhmuc='.$m['id'].'">'.$m['ten_danhmuc'].'</a></li>';
                }
            } 
            ?>
        </ul>
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function createFlower() {
        const flower = document.createElement('div');
        flower.classList.add('tet-flower');
        flower.style.left = Math.random() * 100 + 'vw';
        const size = Math.random() * 10 + 8 + 'px'; 
        flower.style.width = size;
        flower.style.height = size;
        const isApricot = Math.random() > 0.3;
        flower.style.background = isApricot ? '#ffeb3b' : '#ff9ff3'; 
        if(!isApricot) flower.style.borderRadius = '50%'; 
        flower.animate([
            { transform: 'translateY(-10px) rotate(0deg)', opacity: 1 },
            { transform: `translateY(100vh) rotate(${Math.random() * 360}deg)`, opacity: 0 }
        ], { duration: Math.random() * 5000 + 4000, easing: 'linear', fill: 'forwards' });
        document.body.appendChild(flower);
        setTimeout(() => { flower.remove(); }, 9000);
    }
    setInterval(createFlower, 400); 
});
</script>