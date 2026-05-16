<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}
include 'EVA_admin_db.php';

// --- XỬ LÝ XÓA KHÁCH HÀNG ---
if (isset($_GET['action']) && $_GET['action'] == 'xoa' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // 1. Chặn xóa chính mình
    $me = $_SESSION['username'];
    $check_me = mysqli_query($conn, "SELECT id FROM taikhoan WHERE username='$me' AND id=$id");
    
    if(mysqli_num_rows($check_me) > 0) {
        echo "<script>alert('Không thể tự xóa chính mình!'); window.location='EVA_admin_user_manage.php';</script>";
    } else {
        // 2. Thực hiện xóa
        try {
            $sql = "DELETE FROM taikhoan WHERE id=$id";
            if(mysqli_query($conn, $sql)) {
                echo "<script>alert('Đã xóa thành viên!'); window.location='EVA_admin_user_manage.php';</script>";
            }
        } catch(Exception $e) {
            echo "<script>alert('Lỗi: Thành viên này đã có dữ liệu liên quan (đơn hàng), không thể xóa!'); window.location='EVA_admin_user_manage.php';</script>";
        }
    }
}

$sql = "SELECT * FROM taikhoan ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khách hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<?php include 'EVA_admin_menu.php'; ?>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary fw-bold text-uppercase border-start border-5 border-primary ps-3">Danh sách thành viên</h3>
        <span class="badge bg-secondary fs-6">Tổng: <?=mysqli_num_rows($result)?></span>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Thông tin cá nhân</th>
                            <th>Liên hệ</th>
                            <th>Vai trò</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): 
                            $avatar = !empty($row['avatar']) ? '../'.$row['avatar'] : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
                        ?>
                        <tr>
                            <td>#<?=$row['id']?></td>
                            <td>
                                <img src="<?=$avatar?>" class="rounded-circle border" width="50" height="50" style="object-fit:cover;">
                            </td>
                            <td>
                                <div class="fw-bold"><?=$row['hoten']?></div>
                                <small class="text-muted"><i class="fas fa-user-circle me-1"></i> <?=$row['username']?></small>
                            </td>
                            <td>
                                <div><i class="fas fa-envelope text-muted me-2"></i><?=$row['email']?></div>
                                <small><i class="fas fa-phone text-muted me-2"></i><?=!empty($row['so_dien_thoai'])?$row['so_dien_thoai']:'---'?></small>
                            </td>
                            <td>
                                <?php if($row['role'] == 1): ?>
                                    <span class="badge bg-danger">Quản trị viên</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Khách hàng</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($row['role'] != 1): ?>
                                    <a href="EVA_admin_user_manage.php?action=xoa&id=<?=$row['id']?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Bạn có chắc muốn xóa? Dữ liệu không thể phục hồi!');" 
                                       title="Xóa thành viên">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>