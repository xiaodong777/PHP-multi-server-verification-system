<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安全验证系统</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 15%;
        }
        button {
            background-color: #007BFF;
            border: none;
            color: white;
            padding: 15px 30px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    <script>
        function fetchLocationOrPort() {
            // 检测是否为移动设备
            let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

            if (isMobile) {
                window.location.href = "mobile_verification.php";
            } else {
                window.location.href = "desktop_verification.php";
            }
        }
    </script>
</head>

<body>
    <?php
$config = require 'config.php';

$pdo = new PDO("mysql:host={$config['host']};dbname={$config['db']};charset={$config['charset']}", $config['user'], $config['pass']);

$ipAddress = $_SERVER['REMOTE_ADDR'];

$query = $pdo->prepare("SELECT * FROM blocked_ips WHERE ip = ?");
$query->execute([$ipAddress]);

if ($query->rowCount() > 0) {
    header("Location: blockipuser.php");
    exit;
}
?>

    <h1>为确保您的访问安全，我们需要进行验证</h1>
    <p>请点击下方的按钮进行验证。如果您正在使用手机，可能需要提供位置访问权限。</p>
    <button onclick="fetchLocationOrPort()">开始验证</button>
    </br>
    <p>安全验证系统</p>
    
</body>
</html>
