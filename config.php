<?php

$allowed_files = ['index.php', 'blockip.php', 'bad.php', 'api.php'];
$caller = basename($_SERVER['SCRIPT_FILENAME']);

if (!in_array($caller, $allowed_files)) {
    include 'blockip.php';  // 引入blockip.php以获取block_ip函数
    $ip = $_SERVER['REMOTE_ADDR'];
    block_ip($ip);  // 使用函数来阻止IP
    die('Unauthorized access.');
}

return [
    'host' => 'localhost',
    'db'   => 'localhost',
    'user' => 'localhost',
    'pass' => 'localhost',
    'charset' => 'utf8mb4',
];

?>
