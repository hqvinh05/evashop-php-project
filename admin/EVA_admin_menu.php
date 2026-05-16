<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-warning" href="EVA_admin_dashboard.php">
            <i class="fas fa-user-shield me-2"></i>ADMIN EVA SHOP
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?=(strpos($_SERVER['PHP_SELF'], 'dashboard')!==false)?'active text-warning fw-bold':''?>" href="EVA_admin_dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i> Tổng quan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?=(strpos($_SERVER['PHP_SELF'], 'product')!==false)?'active text-warning fw-bold':''?>" href="EVA_admin_product_manage.php">
                        <i class="fas fa-box-open me-1"></i> Sản phẩm
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?=(strpos($_SERVER['PHP_SELF'], 'order')!==false)?'active text-warning fw-bold':''?>" href="EVA_admin_order_manage.php">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Đơn hàng
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?=(strpos($_SERVER['PHP_SELF'], 'user')!==false)?'active text-warning fw-bold':''?>" href="EVA_admin_user_manage.php">
                        <i class="fas fa-users me-1"></i> Khách hàng
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?=(strpos($_SERVER['PHP_SELF'], 'contact')!==false)?'active text-warning fw-bold':''?>" href="EVA_admin_contact.php">
                        <i class="fas fa-envelope me-1"></i> Liên hệ
                    </a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center text-white">
                <span class="me-3"><small>Hello,</small> <strong><?=$_SESSION['admin_name'] ?? 'Admin'?></strong></span>
                <a href="../auth/EVA_auth_logout.php" class="btn btn-sm btn-outline-warning">Thoát</a>
            </div>
        </div>
    </div>
</nav>