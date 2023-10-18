<?php
header('Content-Type: application/json');
$config = require 'config.php';

$dsn = "mysql:host={$config['host']};dbname={$config['db']};charset={$config['charset']}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
} catch (\PDOException $e) {
    die(json_encode(["error" => "Database connection error: " . $e->getMessage()]));
}

$action = $_GET['action'] ?? '';

switch($action) {
    case 'generateToken':
        echo json_encode([
            "status" => "success",  // <-- 这里添加了status字段
            "token" => generateToken($pdo)
        ]);
        break;
    case 'verifyToken':
        $token = $_GET['token'] ?? '';
        echo json_encode(["valid" => verifyToken($token, $pdo)]);
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}

function generateToken($pdo) {
    $token = bin2hex(random_bytes(16));
    
    // 设置 expires_at 为当前时间 + 1 天
    $expires_at = date("Y-m-d H:i:s", strtotime('+1 day'));
    $stmt = $pdo->prepare("INSERT INTO tokens (token, expires_at) VALUES (:token, :expires_at)");
    $stmt->bindParam(":token", $token);
    $stmt->bindParam(":expires_at", $expires_at);
    $stmt->execute();

    return $token;
}

function verifyToken($token, $pdo) {
    $stmt = $pdo->prepare("SELECT expires_at FROM tokens WHERE token = :token");
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    $result = $stmt->fetch();
    if ($result) {
        $expires_at = strtotime($result['expires_at']);
        if ($expires_at > time()) {
            return true;
        }
    }
    return false;
}
?>
