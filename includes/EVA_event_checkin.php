<?php
// LOGIC PHP
if(!isset($_SESSION)) session_start();
if (!isset($conn)) include_once __DIR__ . '/EVA_system_db.php';

$da_diem_danh = false;
$current_streak = 1;
$user_balance = 0;

if(isset($_SESSION['username'])) {
    $u = $_SESSION['username'];
    $today = date('Y-m-d');
    $rs_bal = $conn->query("SELECT diem_tich_luy FROM taikhoan WHERE username='$u'");
    if($rs_bal && $rs_bal->num_rows > 0) $user_balance = $rs_bal->fetch_assoc()['diem_tich_luy'];

    $rs_check = $conn->query("SELECT * FROM lich_su_diem_danh WHERE username='$u' AND ngay_diem_danh='$today'");
    if($rs_check && $rs_check->num_rows > 0) {
        $da_diem_danh = true;
        $row_check = $rs_check->fetch_assoc();
        $current_streak = $row_check['chuoi_ngay'];
    }
}
?>

<div id="checkinModal" class="checkin-modal" style="display: none;">
    <div class="checkin-content shadow-lg">
        <span class="close-btn" onclick="closeCheckinModal()">&times;</span>
        
        <div class="text-center mb-4">
            <h3 class="fw-bold text-white text-uppercase" style="text-shadow: 2px 2px 0px #d35400;">📅 ĐIỂM DANH NHẬN QUÀ</h3>
            <div class="d-inline-block bg-white text-danger px-3 py-1 rounded-pill fw-bold border border-warning">
                💰 Số dư: <?=number_format($user_balance)?> Xu
            </div>
        </div>
        
        <div class="streak-container mb-4">
            <?php for($i=1; $i<=7; $i++): 
                $isActive = ($da_diem_danh && $i == $current_streak) ? 'active' : '';
                $isPast = ($i < $current_streak) ? 'past' : '';
                $coinVal = ($i==7) ? 1500 : ($i < 6 ? $i*100 : 800);
            ?>
                <div class="streak-day <?=$isPast?> <?=$isActive?>" id="day-<?=$i?>">
                    <span class="day-label">Ngày <?=$i?></span>
                    <div class="icon-circle">
                        <?php if($isPast || $isActive): ?>
                            <i class="fas fa-check text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-coins text-warning"></i>
                        <?php endif; ?>
                    </div>
                    <span class="coin-val">+<?=$coinVal?></span>
                </div>
            <?php endfor; ?>
        </div>

        <?php if($da_diem_danh): ?>
            <button class="btn btn-secondary fw-bold w-100 py-3 rounded-pill" disabled>
                <i class="fa fa-check-circle"></i> HÔM NAY ĐÃ NHẬN
            </button>
            <p class="text-white text-center mt-2 small">Quay lại vào ngày mai nhé!</p>
        <?php else: ?>
            <button id="btn-diemdanh" class="btn btn-warning fw-bold w-100 py-3 rounded-pill shadow-sm text-uppercase" onclick="doCheckin()" style="border: 2px solid #fff;">
                <i class="fa fa-hand-pointer"></i> Nhận Ngay
            </button>
            <p id="checkin-msg" class="text-center mt-2 fw-bold text-white"></p>
        <?php endif; ?>
    </div>
</div>

<style>
    .checkin-modal { 
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(0,0,0,0.7); z-index: 10000; 
        display: none; /* Mặc định ẩn */
        align-items: center; justify-content: center; 
        backdrop-filter: blur(5px);
    }
    .checkin-content { 
        background: linear-gradient(180deg, #ff9f43, #ee5253); 
        padding: 30px; border-radius: 20px; 
        width: 95%; max-width: 600px; 
        border: 4px solid #fff; position: relative;
        animation: popupZoom 0.3s ease-out;
    }
    @keyframes popupZoom { from {transform: scale(0.5); opacity: 0;} to {transform: scale(1); opacity: 1;} }
    .close-btn { position: absolute; top: 10px; right: 20px; color: #fff; font-size: 30px; cursor: pointer; opacity: 0.8; }
    .close-btn:hover { opacity: 1; transform: scale(1.1); }
    .streak-container { display: flex; gap: 8px; justify-content: center; overflow-x: auto; }
    .streak-day { background: rgba(255,255,255,0.2); border-radius: 10px; padding: 10px 5px; width: 13%; min-width: 50px; display: flex; flex-direction: column; align-items: center; color: #fff; border: 1px solid rgba(255,255,255,0.3); }
    .streak-day.past { background: rgba(0,0,0,0.2); opacity: 0.7; }
    .streak-day.active { background: #fff !important; color: #d35400 !important; transform: scale(1.1); box-shadow: 0 5px 15px rgba(0,0,0,0.2); border: 2px solid #f1c40f; font-weight: bold; }
    .icon-circle { background: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 5px 0; box-shadow: inset 0 0 5px rgba(0,0,0,0.1); }
    .day-label { font-size: 10px; text-transform: uppercase; } .coin-val { font-size: 11px; font-weight: bold; }
</style>

<script>
    // Định nghĩa hàm mở/đóng popup ngay tại đây
    function openCheckinModal() {
        document.getElementById('checkinModal').style.display = 'flex';
    }
    function closeCheckinModal() {
        document.getElementById('checkinModal').style.display = 'none';
    }

    function doCheckin() {
        var btn = document.getElementById('btn-diemdanh');
        var msg = document.getElementById('checkin-msg');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

        fetch('api/EVA_api_checkin.php', { method: 'POST' })
        .then(response => response.text())
        .then(text => { try { return JSON.parse(text); } catch (e) { throw new Error("Lỗi Server"); } })
        .then(data => {
            if(data.status == 'success') {
                msg.innerHTML = "✅ " + data.message;
                btn.className = 'btn btn-success fw-bold w-100 py-3 rounded-pill shadow';
                btn.innerHTML = '<i class="fa fa-check"></i> THÀNH CÔNG';
                if (typeof confetti === 'function') confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });
                setTimeout(() => { location.reload(); }, 2000);
            } else {
                msg.innerHTML = "❌ " + data.message;
                btn.innerHTML = "THỬ LẠI";
                btn.disabled = false;
            }
        })
        .catch(err => { console.error(err); btn.disabled = false; });
    }
</script>