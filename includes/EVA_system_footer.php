<?php
// Kiểm tra $base_url phòng trường hợp file này được gọi lẻ
if(!isset($base_url)) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/EVASHOP/";
}
?>

<footer class="footer bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase fw-bold text-warning mb-3">EVA SHOP 🏸</h5>
                <p class="small text-white-50 text-justify">
                    Hệ thống cửa hàng cầu lông uy tín hàng đầu Việt Nam. Cung cấp sỉ và lẻ các mặt hàng dụng cụ cầu lông từ phong trào tới chuyên nghiệp.
                </p>
                <div class="mt-3">
                    <a href="#" class="text-white me-3 fs-5"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white me-3 fs-5"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="text-white fs-5"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase fw-bold text-warning mb-3">THÔNG TIN LIÊN HỆ</h5>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>  Số 333 đường Thuận Giao 16, Phường Thuận Giao, TP. Hồ Chí Minh</li>
                    <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> Hotline: <span class="text-white fw-bold">0909.123.456</span></li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> Email: support@evashop.com</li>
                    <li><i class="fas fa-clock me-2"></i> Giờ làm việc: 8:00 - 21:00</li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase fw-bold text-warning mb-3">CHÍNH SÁCH</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Chính sách đổi trả</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Chính sách bảo hành</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Chính sách vận chuyển</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Điều khoản sử dụng</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none hover-white">• Bảo mật thông tin</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase fw-bold text-warning mb-3">HƯỚNG DẪN</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Hướng dẫn mua hàng</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Hướng dẫn thanh toán</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Kiểm tra đơn hàng</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">• Hướng dẫn chọn vợt</a></li>
                    <li><a href="<?=$base_url?>page/EVA_page_contact.php" class="text-white-50 text-decoration-none hover-white">• Liên hệ hỗ trợ</a></li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row">
            <div class="col-12 text-center">
                <p class="small text-white-50 mb-2">© 2025 Công Ty TNHH EVA SPORTS. GPKD số 0314496879.</p>
                <div class="d-inline-block">
                     <img src="<?=$base_url?>images/dathongbao.webp" alt="Đã thông báo BCT" height="50" class="opacity-75">
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Nút tròn để mở chat */
    #chat-toggle-btn { position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: #ffc107; color: #333; border: none; width: 60px; height: 60px; border-radius: 50%; font-size: 24px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.3s; }
    #chat-toggle-btn:hover { transform: scale(1.1); }
    
    /* Cửa sổ chat */
    #chat-window { position: fixed; bottom: 90px; right: 20px; z-index: 9999; width: 350px; height: 500px; background: #fff; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); display: none; flex-direction: column; overflow: hidden; font-family: Arial, sans-serif; border: 1px solid #eee; }
    #chat-header { background: #ffc107; color: #333; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
    #chat-messages { flex: 1; padding: 15px; overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; gap: 10px; }
    
    /* Bong bóng tin nhắn */
    .msg-bubble { padding: 10px 14px; border-radius: 18px; max-width: 80%; font-size: 14px; line-height: 1.4; word-wrap: break-word; }
    .msg-user { background: #007bff; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
    .msg-bot { background: #e9ecef; color: #333; align-self: flex-start; border-bottom-left-radius: 4px; }
    
    /* [MỚI] CSS cho thanh gợi ý */
    #chat-suggestions { display: flex; gap: 8px; padding: 10px; overflow-x: auto; background: #fff; border-top: 1px solid #eee; white-space: nowrap; scrollbar-width: none; }
    #chat-suggestions::-webkit-scrollbar { display: none; } /* Giấu thanh cuộn */
    .suggest-btn { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 6px 12px; border-radius: 15px; font-size: 12px; cursor: pointer; transition: 0.2s; white-space: nowrap; }
    .suggest-btn:hover { background: #ffe8a1; }

    /* Khu vực nhập chữ */
    #chat-input-container { display: flex; padding: 10px; border-top: 1px solid #ddd; background: #fff; }
    #chat-input-text { flex: 1; padding: 10px 15px; border: 1px solid #ccc; border-radius: 20px; outline: none; font-size: 14px; }
    #chat-send-btn { background: none; border: none; color: #ffc107; font-size: 20px; margin-left: 10px; cursor: pointer; transition: 0.2s; }
    #chat-send-btn:hover { color: #d39e00; }
    
    /* CSS hiệu ứng typing */
    .typing-indicator { font-style: italic; color: #888; font-size: 12px; }
</style>

<div id="chat-toggle-btn" onclick="toggleChatWindow()">
    <i class="fas fa-comment-dots"></i>
</div>

<div id="chat-window">
    <div id="chat-header" onclick="toggleChatWindow()">
        <span>🤖 Trợ lý ảo EVASHOP</span>
        <i class="fas fa-times"></i>
    </div>
    <div id="chat-messages">
        <div class="msg-bubble msg-bot">Xin chào! Mình là AI hỗ trợ của EVASHOP. Mình có thể giúp gì cho bạn hôm nay?</div>
    </div>
    
<div id="chat-suggestions">
        <button class="suggest-btn" onclick="sendSuggestion('Tư vấn cho mình các mẫu vợt công giá rẻ')">⚔️ Vợt công giá rẻ</button>
        <button class="suggest-btn" onclick="sendSuggestion('Cho mình xem các loại vợt bán chạy nhất')">🔥 Vợt bán chạy nhất</button>
        <button class="suggest-btn" onclick="sendSuggestion('Tư vấn những loại vợt thủ tốt nhất năm nay')">🛡️ Vợt thủ tốt nhất</button>
    </div>
    <div id="chat-input-container">
        <input type="hidden" id="base_url_hidden" value="<?php echo isset($base_url) ? rtrim($base_url, '/') : 'http://localhost/EVASHOP'; ?>">
        <input type="text" id="chat-input-text" placeholder="Hỏi tui đi..." onkeypress="if(event.key === 'Enter') sendChatMessage()">
        <button id="chat-send-btn" onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
    function toggleChatWindow() {
        var win = document.getElementById('chat-window');
        win.style.display = (win.style.display === 'flex') ? 'none' : 'flex';
    }

    // [MỚI] Hàm xử lý khi khách bấm nút gợi ý
    function sendSuggestion(text) {
        var inputObj = document.getElementById('chat-input-text');
        inputObj.value = text; 
        sendChatMessage();     
        
        // Ẩn thanh gợi ý đi sau khi bấm
        document.getElementById('chat-suggestions').style.display = 'none';
    }

    function sendMSP() {
        sendSuggestion('Hướng dẫn mình cách thanh toán khi mua hàng');
    }

    // Nâng cấp: Đã thêm tính năng khóa nút chống Spam Click
    function sendChatMessage() {
        var inputObj = document.getElementById('chat-input-text');
        var message = inputObj.value.trim();
        var sendBtn = document.getElementById('chat-send-btn');
        
        // Nếu không có chữ, hoặc nút đang bị khóa thì không làm gì cả
        if (!message || sendBtn.disabled) return;

        var msgBox = document.getElementById('chat-messages');
        var baseUrl = document.getElementById('base_url_hidden').value;
        var apiUrl = baseUrl + '/api/EVA_api_bot.php';

        // Khóa nút gửi + Khóa ô nhập liệu
        sendBtn.disabled = true;
        inputObj.disabled = true;

        // 1. In tin nhắn người dùng ra màn hình
        msgBox.innerHTML += '<div class="msg-bubble msg-user">' + message + '</div>';
        inputObj.value = '';
        msgBox.scrollTop = msgBox.scrollHeight;

        // 2. In chữ "Đang gõ..."
        var typingId = 'typing-' + Date.now();
        msgBox.innerHTML += '<div class="msg-bubble msg-bot typing-indicator" id="' + typingId + '">Đang suy nghĩ... <i class="fas fa-spinner fa-spin"></i></div>';
        msgBox.scrollTop = msgBox.scrollHeight;

        // 3. Gửi Ajax lên Backend
        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Thay thế chữ "Đang gõ..." bằng kết quả thật
            var typingElement = document.getElementById(typingId);
            typingElement.className = 'msg-bubble msg-bot';
            typingElement.innerHTML = data.reply;
            msgBox.scrollTop = msgBox.scrollHeight;
        })
        .catch(error => {
            document.getElementById(typingId).innerHTML = 'Xin lỗi, kết nối mạng đang gặp sự cố!';
            document.getElementById(typingId).className = 'msg-bubble msg-bot text-danger';
        })
        .finally(() => {
            // [QUAN TRỌNG] Mở khóa cho khách chat tiếp sau khi có kết quả
            sendBtn.disabled = false;
            inputObj.disabled = false;
            inputObj.focus(); 
        });
    }
</script>