<?php
session_start();
include_once '../includes/EVA_system_db.php';

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$amount_expected = isset($_POST['amount']) ? intval($_POST['amount']) : 0;

if ($order_id == 0) {
    echo json_encode(['status' => 'error', 'msg' => 'Không tìm thấy ID đơn hàng']);
    exit();
}

// ==========================================
// DÁN MÃ API TOKEN SEPAY CỦA BẠN VÀO DÒNG DƯỚI NÀY
// ==========================================
$sepay_token = '********'; 

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://my.sepay.vn/userapi/transactions/list?limit=10',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer ' . $sepay_token,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
$is_paid = false;

if (isset($data['transactions'])) {
    foreach ($data['transactions'] as $tran) {
        $content = strtoupper($tran['transaction_content']); 
        $money = intval($tran['amount_in']); 
        
        $expected_string = "EVASHOP DH" . $order_id; 

        // Kiểm tra đúng nội dung và đủ tiền
        if (strpos($content, $expected_string) !== false && $money >= $amount_expected) {
            $is_paid = true;
            break;
        }
    }
}

if ($is_paid) {
    $conn->query("UPDATE donhang SET trang_thai = 'Đã thanh toán' WHERE id = $order_id");
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'pending']);
}
?>
