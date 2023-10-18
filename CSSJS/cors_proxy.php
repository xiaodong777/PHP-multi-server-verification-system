<?php
// 设置响应头部，允许任何来源的访问
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';  
$token = $_GET['token'] ?? '';

$internal_url = "http://Intranet_address/verification/api.php?action=$action";
if ($action === 'verifyToken' && $token) {
    $internal_url .= "&token=$token";
}

// 为cURL初始化会话
$ch = curl_init($internal_url);

// 设置cURL选项
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
curl_setopt($ch, CURLOPT_TIMEOUT, 10);          
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

http_response_code($httpCode);

// 检查从内部API返回的数据
$data = json_decode($response, true);

// 记录日志
$logMessage = sprintf(
    "[%s] Request to %s - HTTP Status: %s - Response: %s - cURL Error: %s\n",
    date("Y-m-d H:i:s"),
    $internal_url,
    $httpCode,
    $response,
    $curlError
);

file_put_contents(__DIR__ . '/cors_proxy.log', $logMessage, FILE_APPEND);

if ($httpCode == 200) {
    echo $response; 
} else {
    echo json_encode(["error" => "Unable to fetch from internal API"]);
}
?>
