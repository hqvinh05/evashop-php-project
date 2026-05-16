<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 1) { 
    header("Location: ../auth/EVA_auth_login.php"); 
    exit(); 
}

if(file_exists('EVA_admin_db.php')) include 'EVA_admin_db.php';
else include '../includes/EVA_system_db.php'; 

// ==========================================
// 1. TÍNH TOÁN DỮ LIỆU CHO CÁC BIỂU ĐỒ BI
// ==========================================

// --- A. BIỂU ĐỒ NỬA TRÒN (GAUGE): TỶ LỆ HẾT HÀNG & CHẠM NGƯỠNG ---
$sql_stock_stats = "SELECT 
    COUNT(*) as total_sp,
    SUM(CASE WHEN so_luong <= 0 THEN 1 ELSE 0 END) as sp_het_hang,
    SUM(CASE WHEN so_luong > 0 AND so_luong <= 10 THEN 1 ELSE 0 END) as sp_cham_nguong
    FROM sanpham";
$stock_stats = mysqli_fetch_assoc(mysqli_query($conn, $sql_stock_stats));

$total_sp = $stock_stats['total_sp'] > 0 ? $stock_stats['total_sp'] : 1;
$ty_le_het_hang = round(($stock_stats['sp_het_hang'] / $total_sp) * 100, 1);
$so_mon_cham_nguong = $stock_stats['sp_cham_nguong'];

// --- B. BIỂU ĐỒ CỘT NGANG: TỔNG QUAN TỒN KHO & DOANH THU (TOP 7) ---
$labels_top = []; $data_stock = []; $data_revenue = [];
$sql_top_7 = "
    SELECT s.ten_sanpham, s.so_luong, IFNULL(SUM(ct.so_luong * ct.don_gia), 0) as doanh_thu
    FROM sanpham s
    LEFT JOIN chitietdonhang ct ON s.id = ct.sanpham_id
    LEFT JOIN donhang d ON ct.donhang_id = d.id AND d.trang_thai != 'Hủy'
    GROUP BY s.id
    ORDER BY doanh_thu DESC LIMIT 7
";
$rs_top_7 = mysqli_query($conn, $sql_top_7);
while($row = mysqli_fetch_assoc($rs_top_7)) {
    $labels_top[] = mb_strimwidth($row['ten_sanpham'], 0, 25, "...");
    $data_stock[] = $row['so_luong'];
    $data_revenue[] = $row['doanh_thu'];
}

// --- C. MA TRẬN ABC/XYZ (PHÂN LOẠI HÀNG HÓA) ---
// Phân loại đơn giản: 
// A, B, C dựa trên Doanh thu (A > 2tr, B: 500k-2tr, C: < 500k)
// X, Y, Z dựa trên Tồn kho (1: X > 50, 2: Y 10-50, 3: Z < 10)
$matrix = ['A1'=>0, 'A2'=>0, 'A3'=>0, 'B1'=>0, 'B2'=>0, 'B3'=>0, 'C1'=>0, 'C2'=>0, 'C3'=>0];

$sql_matrix = "
    SELECT s.so_luong, IFNULL(SUM(ct.so_luong * ct.don_gia), 0) as rev
    FROM sanpham s
    LEFT JOIN chitietdonhang ct ON s.id = ct.sanpham_id
    LEFT JOIN donhang d ON ct.donhang_id = d.id AND d.trang_thai != 'Hủy'
    GROUP BY s.id
";
$rs_matrix = mysqli_query($conn, $sql_matrix);
while($r = mysqli_fetch_assoc($rs_matrix)) {
    $rev = $r['rev']; $qty = $r['so_luong'];
    $row = 'C'; if($rev >= 2000000) $row = 'A'; elseif($rev >= 500000) $row = 'B';
    $col = '3'; if($qty >= 50) $col = '1'; elseif($qty >= 10) $col = '2';
    $matrix[$row.$col]++;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>BI Inventory Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .bi-title { font-weight: 800; color: #333; text-transform: uppercase; font-size: 18px; border-left: 5px solid #ffc107; padding-left: 10px; margin-bottom: 20px;}
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        /* CSS CHUẨN CHO MA TRẬN ABC/XYZ */
        .abc-grid { display: grid; grid-template-columns: 30px 1fr 1fr 1fr; gap: 8px; align-items: center; text-align: center; }
        .abc-cell { padding: 15px 5px; border-radius: 6px; font-weight: bold; color: white; display: flex; flex-direction: column; justify-content: center; height: 80px;}
        .abc-cell span { font-size: 24px; }
        .abc-cell small { font-size: 11px; opacity: 0.8; font-weight: normal;}
        
        .bg-a1 { background-color: #d69e2e; } /* Vàng đậm */
        .bg-a2 { background-color: #6c757d; } /* Xám */
        .bg-c1 { background-color: #adb5bd; } /* Xám nhạt */
        
        .bg-a2-mid { background-color: #858796; } 
        .bg-b2 { background-color: #f6c23e; } /* Vàng sáng */
        .bg-c2 { background-color: #b1b3b8; }
        
        .bg-c1-low { background-color: #e2e3e5; color: #555;}
        .bg-c2-low { background-color: #d1d3e2; color: #555;}
        .bg-c3 { background-color: #eaecf4; color: #555;}
        
        .grid-y-axis { transform: rotate(-90deg); font-weight: bold; color: #666; font-size: 12px; white-space: nowrap; height: 20px; width: 100px; margin-left: -35px;}
        .grid-x-axis { grid-column: 2 / 5; display: flex; justify-content: space-between; font-weight: bold; color: #666; font-size: 12px; margin-top: 5px;}
        
        .gauge-container { position: relative; width: 100%; height: 180px; display: flex; justify-content: center; }
        .gauge-text { position: absolute; bottom: 10px; text-align: center;}
        .gauge-text h2 { margin: 0; font-weight: 900; font-size: 32px; color: #e74a3b; }
        .gauge-text-blue h2 { color: #4e73df; }
    </style>
</head>
<body class="bg-light">

<?php include 'EVA_admin_menu.php'; ?>

<div class="container-fluid px-4 mt-4 mb-5">
    
    <div class="mb-4 text-center">
        <h2 class="fw-bold" style="color: #2c3e50;">Trí tuệ doanh nghiệp (BI): Loại bỏ quản lý tồn kho cảm tính</h2>
        <p class="text-muted">Tối ưu hóa vòng quay vốn, giảm chi phí tồn kho và nâng cao hiệu quả chuỗi cung ứng.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card card-custom h-100 p-4">
                <div class="bi-title">Tổng quan Tồn kho & Doanh thu</div>
                <div style="height: 400px; width: 100%;">
                    <canvas id="inventoryRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="row g-4">
                <div class="col-6">
                    <div class="card card-custom p-3 text-center h-100">
                        <div class="fw-bold text-muted mb-2">Tỷ Lệ Hết Hàng</div>
                        <div class="gauge-container">
                            <canvas id="gaugeStockout"></canvas>
                            <div class="gauge-text">
                                <h2><?=$ty_le_het_hang?>%</h2>
                                <small class="text-danger fw-bold">NGUY CƠ</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="card card-custom p-3 text-center h-100">
                        <div class="fw-bold text-muted mb-2">Ngưỡng Tồn Kho Tối Thiểu</div>
                        <div class="gauge-container">
                            <canvas id="gaugeThreshold"></canvas>
                            <div class="gauge-text gauge-text-blue">
                                <h2><?=$so_mon_cham_nguong?></h2>
                                <small class="text-primary fw-bold">Món Chạm Ngưỡng</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card card-custom p-4">
                        <div class="bi-title mb-2">Phân khúc ABC/XYZ</div>
                        <small class="text-muted d-block mb-3"><i class="fas fa-circle text-warning"></i> High revenue/turnover &nbsp; <i class="fas fa-circle text-secondary"></i> Mid &nbsp; <i class="fas fa-circle" style="color:#e2e3e5;"></i> Low</small>
                        
                        <div class="abc-grid">
                            <div class="grid-y-axis">Doanh thu (ABC)</div>
                            <div class="abc-cell bg-a1">A1<span><?=$matrix['A1']?></span></div>
                            <div class="abc-cell bg-a2">A2<span><?=$matrix['A2']?></span></div>
                            <div class="abc-cell bg-c1">A3<span><?=$matrix['A3']?></span></div>
                            
                            <div></div>
                            <div class="abc-cell bg-a2-mid">B1<span><?=$matrix['B1']?></span></div>
                            <div class="abc-cell bg-b2">B2<span><?=$matrix['B2']?></span></div>
                            <div class="abc-cell bg-c2">B3<span><?=$matrix['B3']?></span></div>
                            
                            <div></div>
                            <div class="abc-cell bg-c1-low">C1<span><?=$matrix['C1']?></span></div>
                            <div class="abc-cell bg-c2-low">C2<span><?=$matrix['C2']?></span></div>
                            <div class="abc-cell bg-c3">C3<span><?=$matrix['C3']?></span></div>
                            
                            <div class="grid-x-axis">
                                <span>1 (Tồn kho cao)</span>
                                <span>2 (Vừa)</span>
                                <span>3 (Tồn kho thấp)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. BIỂU ĐỒ CỘT NGANG: TỒN KHO & DOANH THU (2 TRỤC X)
    const ctx1 = document.getElementById('inventoryRevenueChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: <?=json_encode($labels_top)?>,
            datasets: [
                {
                    label: 'Tồn kho (Cái)',
                    data: <?=json_encode($data_stock)?>,
                    backgroundColor: '#858796', // Màu xám
                    yAxisID: 'y',
                    xAxisID: 'x-stock',
                    barPercentage: 0.4
                },
                {
                    label: 'Doanh thu (VNĐ)',
                    data: <?=json_encode($data_revenue)?>,
                    backgroundColor: '#f6c23e', // Màu vàng
                    yAxisID: 'y',
                    xAxisID: 'x-revenue',
                    barPercentage: 0.4
                }
            ]
        },
        options: {
            indexAxis: 'y', // Xoay ngang
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                'x-stock': { 
                    position: 'top', 
                    title: { display: true, text: 'Tồn kho' },
                    grid: { display: false }
                },
                'x-revenue': { 
                    position: 'bottom', 
                    title: { display: true, text: 'Doanh thu (VNĐ)' },
                    ticks: { callback: function(val) { return (val/1000000) + ' Tr'; } }
                }
            },
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // CẤU HÌNH CHUNG CHO BIỂU ĐỒ GAUGE (NỬA HÌNH TRÒN)
    const gaugeOptions = {
        rotation: 270, // Bắt đầu từ bên trái
        circumference: 180, // Vẽ 1 nửa hình tròn
        cutout: '80%', // Lỗ rỗng ở giữa
        maintainAspectRatio: false,
        plugins: { tooltip: { enabled: false }, legend: { display: false } }
    };

    // 2. GAUGE: TỶ LỆ HẾT HÀNG
    new Chart(document.getElementById('gaugeStockout'), {
        type: 'doughnut',
        data: {
            labels: ['Hết hàng', 'Còn hàng'],
            datasets: [{
                data: [<?=$ty_le_het_hang?>, <?=100 - $ty_le_het_hang?>],
                backgroundColor: ['#e74a3b', '#eaecf4'],
                borderWidth: 0
            }]
        },
        options: gaugeOptions
    });

    // 3. GAUGE: MÓN CHẠM NGƯỠNG
    new Chart(document.getElementById('gaugeThreshold'), {
        type: 'doughnut',
        data: {
            labels: ['Chạm ngưỡng', 'An toàn'],
            datasets: [{
                data: [<?=$so_mon_cham_nguong?>, <?=$total_sp - $so_mon_cham_nguong?>],
                backgroundColor: ['#4e73df', '#eaecf4'],
                borderWidth: 0
            }]
        },
        options: gaugeOptions
    });
</script>

</body>
</html>