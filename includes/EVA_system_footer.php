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