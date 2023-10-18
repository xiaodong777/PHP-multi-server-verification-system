<?php

function block_ip($ip) {
    global $pdo;  // 使用全局变量$pdo
    $query = $pdo->prepare("INSERT INTO blocked_ips (ip) VALUES (?)");
    $query->execute([$ip]);
}

// 当该页面被直接访问时，也将IP添加到数据库
if (basename($_SERVER['SCRIPT_FILENAME']) == 'blockip.php') {
    $config = require 'config.php';
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['db']};charset={$config['charset']}", $config['user'], $config['pass']);
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    block_ip($ipAddress);
}

?>
