<?php
session_start();
include '../includes/EVA_system_db.php';

$u = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$xu = 0;
$luot_free = false;

if($u) {
    $res = $conn->query("SELECT diem_tich_luy, ngay_quay_free FROM taikhoan WHERE username='$u'");
    if($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $xu = intval($row['diem_tich_luy']); // Đảm bảo là số nguyên
        if($row['ngay_quay_free'] != date('Y-m-d')) $luot_free = true;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Vòng Quay May Mắn</title>
    <?php include '../includes/EVA_system_header.php'; ?>
    <style>
        .game-wrapper {
            background: radial-gradient(circle, #ff9f43, #ee5253);
            min-height: 80vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 20px;
            background-image: repeating-conic-gradient(#ff9f43 0 15deg, #ee5253 15deg 30deg);
        }
        .wheel-border {
            position: relative; padding: 15px; background: #fff; border-radius: 50%;
            box-shadow: 0 0 20px rgba(0,0,0,0.5); border: 8px dashed #f1c40f;
        }
        .wheel-container { position: relative; width: 400px; height: 400px; margin: 0 auto; }
        #canvas { width: 100%; height: 100%; transition: transform 4s cubic-bezier(0.25, 0.1, 0.25, 1); border-radius: 50%; }
        .arrow { 
            position: absolute; top: -25px; left: 50%; transform: translateX(-50%); 
            width: 50px; height: 50px; 
            background-image: url('https://cdn-icons-png.flaticon.com/512/25/25623.png');
            background-size: contain; background-repeat: no-repeat;
            z-index: 10; filter: drop-shadow(0 5px 5px rgba(0,0,0,0.3));
        }
        .spin-btn { 
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
            width: 90px; height: 90px; 
            background: radial-gradient(circle, #fff, #f1f2f6); 
            border-radius: 50%; border: 6px solid #e74c3c; 
            font-weight: 800; cursor: pointer; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 5; 
            font-size: 20px; color: #c0392b; text-transform: uppercase;
        }
        .spin-btn:active { transform: translate(-50%, -50%) scale(0.95); }
        .info-box { background: rgba(255,255,255,0.9); padding: 15px 30px; border-radius: 50px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<div class="game-wrapper">
    <div class="info-box mb-4 text-center">
        <h2 class="fw-bold text-danger m-0 text-uppercase">Vòng Quay May Mắn</h2>
        <div class="mt-2 text-dark">
            Số dư: <span class="badge bg-warning text-dark fs-6"><?=number_format($xu)?> Xu</span>
        </div>
    </div>

    <div class="mb-3 text-center">
        <?php if($luot_free): ?>
            <div class="badge bg-success fs-5 px-4 py-2 shadow animate__animated animate__pulse animate__infinite">
                ✨ BẠN CÓ 1 LƯỢT MIỄN PHÍ! ✨
            </div>
        <?php else: ?>
            <div class="badge bg-dark fs-6 px-3 py-2 opacity-75">
                Phí quay: 500 Xu / lượt
            </div>
        <?php endif; ?>
    </div>

    <div class="wheel-border">
        <div class="wheel-container">
            <div class="arrow"></div>
            <canvas id="canvas" width="500" height="500"></canvas>
            <button class="spin-btn" onclick="spinWheel()" id="btnSpin">
                <?= $luot_free ? "FREE" : "QUAY" ?>
            </button>
        </div>
    </div>
    
    <div id="result_msg" class="mt-4 fw-bold fs-3 text-white text-shadow" style="text-shadow: 2px 2px 4px #000;"></div>
    
    <div class="mt-4">
        <a href="../index.php" class="btn btn-light fw-bold rounded-pill px-4"><i class="fas fa-arrow-left"></i> Về Trang Chủ</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    // --- QUAN TRỌNG: Lấy số dư từ PHP truyền qua JS ---
    var userPoint = <?php echo $xu; ?>;
    var isFree = <?php echo $luot_free ? 'true' : 'false'; ?>;

    const prizes = [
        { label: "Chúc may mắn", color: "#ecf0f1", text: "#7f8c8d" }, 
        { label: "10K", color: "#3498db", text: "#fff" }, 
        { label: "20K", color: "#2ecc71", text: "#fff" }, 
        { label: "50K", color: "#f1c40f", text: "#fff" }, 
        { label: "100K", color: "#e74c3c", text: "#fff" } 
    ];
    
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const arc = 2 * Math.PI / prizes.length; 
    let currentRotation = 0;

    function drawWheel() {
        ctx.clearRect(0, 0, 500, 500);
        const radius = 250;
        const startOffset = -Math.PI / 2;

        prizes.forEach((prize, i) => {
            const angle = startOffset + (i * arc);
            ctx.beginPath();
            ctx.fillStyle = prize.color;
            ctx.moveTo(250, 250);
            ctx.arc(250, 250, radius, angle, angle + arc);
            ctx.lineTo(250, 250);
            ctx.fill();
            
            ctx.strokeStyle = "#fff"; ctx.lineWidth = 4; ctx.stroke();

            ctx.save();
            ctx.translate(250 + Math.cos(angle + arc / 2) * 160, 250 + Math.sin(angle + arc / 2) * 160);
            ctx.rotate(angle + arc / 2 + Math.PI / 2);
            ctx.fillStyle = prize.text;
            ctx.font = "bold 28px Arial";
            ctx.fillText(prize.label, -ctx.measureText(prize.label).width / 2, 0);
            ctx.restore();
        });
    }
    drawWheel();

    function spinWheel() {
        const btn = document.getElementById('btnSpin');
        const msg = document.getElementById('result_msg');
        
        // KIỂM TRA SỐ DƯ CHÍNH XÁC
        if(!isFree && userPoint < 500) { 
            alert("Bạn không đủ 500 xu! (Số dư: " + new Intl.NumberFormat().format(userPoint) + ")"); 
            return; 
        }

        btn.disabled = true; msg.innerHTML = "";

        fetch('../api/EVA_api_spin.php')
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                // Cập nhật số dư JS ngay lập tức
                userPoint = data.new_balance; 
                
                const index = data.prize_index;
                const sliceDeg = 360 / prizes.length;
                const targetRotation = 360 * 10 - (index * sliceDeg) - (sliceDeg / 2);
                const currentMod = currentRotation % 360;
                currentRotation += (targetRotation - currentMod) + 3600; 

                canvas.style.transform = `rotate(${currentRotation}deg)`;

                setTimeout(() => {
                    if(data.prize_name !== 'Chúc may mắn') {
                        msg.innerHTML = `🎉 BẠN TRÚNG ${data.prize_name}`;
                        confetti({ particleCount: 200, spread: 100, origin: { y: 0.6 } });
                    } else {
                        msg.innerHTML = `😅 CHÚC MAY MẮN LẦN SAU`;
                    }
                    btn.disabled = false;
                    if(data.is_free_used) {
                        isFree = false;
                        btn.innerText = "QUAY";
                    }
                }, 4000);
            } else {
                alert(data.message);
                btn.disabled = false;
            }
        });
    }
</script>
</body>
</html>