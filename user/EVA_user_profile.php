<?php
session_start();
include '../includes/EVA_system_db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/EVA_auth_login.php");
    exit();
}

$u = $_SESSION['username'];
$msg = "";

// XỬ LÝ CẬP NHẬT THÔNG TIN (Giữ nguyên)
if (isset($_POST['btnSave'])) {
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];
    
    $avatar_sql = "";
    if(isset($_FILES['avatar']) && $_FILES['avatar']['name'] != "") {
        $target_dir = "../uploads/";
        $fname = time() . "_" . basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . $fname;
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
        $avatar_sql = ", avatar = 'uploads/$fname'";
    }

    $sql = "UPDATE taikhoan SET hoten='$hoten', email='$email', so_dien_thoai='$sdt', dia_chi='$diachi' $avatar_sql WHERE username='$u'";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Cập nhật thành công!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}

// LẤY THÔNG TIN USER
$result = $conn->query("SELECT * FROM taikhoan WHERE username='$u'");
$user = $result->fetch_assoc();

// --- LOGIC MỚI: TÁCH BIỆT XU VÀ RANK ---

// 1. Số dư để tiêu (Ví tiền)
$xu_kha_dung = $user['diem_tich_luy']; 

// 2. Điểm kinh nghiệm để tính cấp (Cột mới)
// Nếu chưa chạy lệnh SQL thêm cột thì mặc định lấy điểm tích lũy đỡ
$diem_exp = isset($user['diem_thanh_vien']) ? $user['diem_thanh_vien'] : $user['diem_tich_luy'];

// 3. Tính Rank dựa trên $diem_exp (KHÔNG PHẢI $xu_kha_dung)
$rank = "Thành Viên Mới";
$color = "secondary";
$next_rank_xu = 1000;
$current_level_base = 0; // Mốc điểm của cấp hiện tại

if($diem_exp >= 1000 && $diem_exp < 5000) { 
    $rank = "Thành Viên Bạc"; $color = "info"; 
    $current_level_base = 1000;
    $next_rank_xu = 5000; 
}
elseif($diem_exp >= 5000 && $diem_exp < 10000) { 
    $rank = "Thành Viên Vàng"; $color = "warning"; 
    $current_level_base = 5000;
    $next_rank_xu = 10000; 
}
elseif($diem_exp >= 10000) { 
    $rank = "VIP Kim Cương"; $color = "danger"; 
    $current_level_base = 10000;
    $next_rank_xu = 100000; // Cấp max
}

// 4. Tính % thanh tiến độ chính xác hơn
// Công thức: (Điểm hiện tại - Mốc cấp hiện tại) / (Mốc cấp tiếp theo - Mốc cấp hiện tại) * 100
if($diem_exp >= 10000) {
    $percent = 100; // Max cấp
    $next_rank_display = "MAX LEVEL";
} else {
    $percent = (($diem_exp - $current_level_base) / ($next_rank_xu - $current_level_base)) * 100;
    $next_rank_display = number_format($next_rank_xu) . " XP";
}

// Đếm đơn hàng
$rs_don = $conn->query("SELECT COUNT(*) as total FROM donhang WHERE username='$u'");
$total_orders = $rs_don->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ cá nhân - EVA SHOP</title>
    <?php include '../includes/EVA_system_header.php'; ?>
    <style>
        .profile-card {
            background: #fff; border-radius: 15px; overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; padding-bottom: 20px;
        }
        .profile-header {
            background: linear-gradient(135deg, #212529, #495057); height: 100px; margin-bottom: 60px; position: relative;
        }
        .avatar-box {
            position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%);
            width: 120px; height: 120px; border-radius: 50%; border: 5px solid #fff;
            background: #fff; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.2); cursor: pointer;
        }
        .avatar-label { display: block; width: 100%; height: 100%; margin: 0; cursor: pointer; position: relative; }
        .avatar-label img { width: 100%; height: 100%; object-fit: cover; transition: 0.3s; }
        .avatar-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); color: #fff;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            opacity: 0; transition: 0.3s;
        }
        .avatar-box:hover .avatar-overlay { opacity: 1; }
        .wallet-box {
            background: linear-gradient(45deg, #ffc107, #ff9800);
            color: #fff; margin: 20px; padding: 15px; border-radius: 12px;
            position: relative; overflow: hidden;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
        }
        .wallet-box::after {
            content: "💰"; position: absolute; right: -10px; bottom: -10px; font-size: 80px; opacity: 0.2; transform: rotate(-20deg);
        }
        .stat-row { display: flex; justify-content: space-around; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px; }
        .stat-item h4 { font-weight: bold; margin: 0; font-size: 18px; }
        .stat-item span { font-size: 12px; color: #777; }
    </style>
</head>
<body class="bg-light">
    
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar-box" title="Bấm để đổi ảnh đại diện">
                            <label for="upload_avatar" class="avatar-label">
                                <img src="../<?php echo !empty($user['avatar']) ? $user['avatar'] : 'images/default_avatar.png'; ?>" id="preview_img">
                                <div class="avatar-overlay"><i class="fas fa-camera"></i><span>Đổi ảnh</span></div>
                            </label>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-1 mt-2"><?=$user['hoten']?></h5>
                    <span class="badge bg-<?=$color?> mb-3"><?=$rank?></span>
                    
                    <div class="wallet-box text-start">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-uppercase" style="opacity: 0.9;">Số dư ví</div>
                            <div class="small fw-bold"><i class="fas fa-star me-1"></i>EXP: <?=number_format($diem_exp)?></div>
                        </div>
                        
                        <div class="fs-2 fw-bold"><?=number_format($xu_kha_dung)?> <span class="fs-6">Xu</span></div>
                        
                        <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-white" style="width: <?=$percent?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1" style="font-size: 10px; opacity: 0.9;">
                            <span>Tiến độ lên cấp</span>
                            <span>Mục tiêu: <?=$next_rank_display?></span>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="stat-item"><h4><?=$total_orders?></h4><span>Đơn hàng</span></div>
                        <div class="stat-item"><h4><?=date('d/m/Y')?></h4><span>Tham gia</span></div>
                    </div>
                    
                    <div class="d-grid gap-2 px-3 mt-3">
                        <a href="../product/EVA_product_list.php" class="btn btn-outline-dark btn-sm rounded-pill">
                            <i class="fas fa-gift me-1"></i> Đổi xu lấy quà ngay
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h4 class="fw-bold text-uppercase"><i class="fas fa-user-edit text-warning me-2"></i>Chỉnh sửa hồ sơ</h4>
                        <a href="EVA_user_orders.php" class="btn btn-light text-primary fw-bold btn-sm"><i class="fas fa-receipt me-1"></i> Xem đơn hàng</a>
                    </div>
                    <?=$msg?>
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="avatar" id="upload_avatar" hidden onchange="document.getElementById('preview_img').src = window.URL.createObjectURL(this.files[0])">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small text-muted">Họ và tên</label><input type="text" name="hoten" class="form-control" value="<?=$user['hoten']?>" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small text-muted">Số điện thoại</label><input type="text" name="sdt" class="form-control" value="<?=$user['so_dien_thoai']?>"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold small text-muted">Email</label><input type="email" name="email" class="form-control bg-light" value="<?=$user['email']?>" readonly></div>
                        <div class="mb-4"><label class="form-label fw-bold small text-muted">Địa chỉ nhận hàng</label><textarea name="diachi" class="form-control" rows="3"><?=$user['dia_chi']?></textarea></div>
                        <div class="text-end">
                            <a href="../index.php" class="btn btn-secondary me-2">Về trang chủ</a>
                            <button type="submit" name="btnSave" class="btn btn-warning fw-bold px-4 shadow-sm"><i class="fas fa-save me-2"></i>LƯU THÔNG TIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/EVA_system_footer.php'; ?>
</body>
</html>