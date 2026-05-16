<?php
function execPostRequest($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    
    // FIX LỖI SSL LOCALHOST
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);

    if ($result === false) {
        echo "<div style='background:red; color:white; padding:10px;'>CURL Error: " . curl_error($ch) . "</div>";
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);
    return $result;
}

function thanhToanMoMo($orderId, $amount, $customOrderInfo = "") {
    
    // 1. CẤU HÌNH MOMO (KEY WEB SÁCH)
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = "MOMOBKUN20180529";
    $accessKey = "klm05TvNBzhg7h7j";
    $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
    
    // 2. TỰ ĐỘNG LẤY TÊN MIỀN
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $base_url = $protocol . "://" . $host . "/EVASHOP/";

    // 3. TẠO MÃ ĐƠN HÀNG DUY NHẤT (QUAN TRỌNG)
    // MoMo không cho phép trùng ID, nên ta nối thêm time() vào: VD 30_1708505050
    $momoOrderId = $orderId . "_" . time();

    // Link trả về: Gửi kèm ID gốc để trang đích xử lý
    $redirectUrl = $base_url . "page/EVA_page_order_success.php";
    $ipnUrl = $base_url . "cart/EVA_ipn_momo.php"; 

    $orderInfo = ($customOrderInfo != "") ? $customOrderInfo : "Thanh toan don hang #" . $orderId;
    $requestId = time() . "";
    $requestType = "captureWallet";
    $extraData = "";

    // 4. TẠO CHỮ KÝ (Dùng $momoOrderId)
    $rawHash = "accessKey=" . $accessKey . 
               "&amount=" . $amount . 
               "&extraData=" . $extraData . 
               "&ipnUrl=" . $ipnUrl . 
               "&orderId=" . $momoOrderId . 
               "&orderInfo=" . $orderInfo . 
               "&partnerCode=" . $partnerCode . 
               "&redirectUrl=" . $redirectUrl . 
               "&requestId=" . $requestId . 
               "&requestType=" . $requestType;

    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    // 5. GÓI JSON
    $data = array(
        'partnerCode' => $partnerCode,
        'partnerName' => "EVA SHOP",
        'storeId' => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $momoOrderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    );
    
    // 6. GỬI REQUEST
    $result = execPostRequest($endpoint, json_encode($data));
    
    if ($result) {
        $jsonResult = json_decode($result, true);
        if (isset($jsonResult['payUrl'])) {
            header('Location: ' . $jsonResult['payUrl']);
            exit();
        } else {
            echo "<div style='color:red; font-weight:bold; padding:20px; border:1px solid red; margin:20px;'>";
            echo "LỖI MOMO API:<br>";
            echo "Message: " . ($jsonResult['message'] ?? 'Không có message') . "<br>";
            echo "Local Message: " . ($jsonResult['localMessage'] ?? '') . "<br>";
            echo "</div>";
            // echo "<pre>"; print_r($jsonResult); echo "</pre>";
        }
    }
}
?>