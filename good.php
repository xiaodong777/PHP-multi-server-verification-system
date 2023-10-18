<?php
$response = file_get_contents('https://www.com/verification/cors_proxy.php');
$data = json_decode($response, true);
$token = $data['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>验证成功</title>
    <script>
        setTimeout(function(){
            window.location.href = 'https://目的网站.com/index.jsp?token=<?php echo $token; ?>';
        }, 300);
    </script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <h1 class="display-4 text-success">验证成功</h1>
                        <p class="lead">系统将在3秒内跳转会您访问的平台。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
