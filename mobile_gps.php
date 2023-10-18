<?php
// mobile_gps.php

// 预设的定位信息
$predefined_latitude = 22.572891322805123;  // 预定义纬度
$predefined_longitude = 114.12345537005885; // 预定义经度

$verification_result = "未知";

if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $user_latitude = $_POST['latitude'];
    $user_longitude = $_POST['longitude'];

    // 对位置进行简单比对
    $tolerance = 0.01;

    if (abs($user_latitude - $predefined_latitude) <= $tolerance && abs($user_longitude - $predefined_longitude) <= $tolerance) {
        $verification_result = "通过";
    } else {
        $verification_result = "不通过";
    }

    // 返回结果给 mobile_verification.php
    echo $verification_result;
    exit;
}
?>
