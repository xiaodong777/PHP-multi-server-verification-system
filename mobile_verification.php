<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>手机端安全环境验证</title>
    <!-- 引入 Bootstrap CSS -->
    <link rel="stylesheet" href="CSSJS/bootstrap.min.css">
    <!-- 引入 Font Awesome 图标库 -->
    <link rel="stylesheet" href="CSSJS/all.min.css">
</head>
<body>

<div class="container text-center" style="margin-top: 50px;">
    <h1>手机端安全环境验证</h1>
    <p id="info-text" class="mt-4">为确保您的上网环境安全，我们需要获取您当前的定位信息，请允许浏览器定位权限。</p>
    <button id="start-btn" onclick="triggerLocation()">开始进行安全环境检查</button>
    <p id="notice-text" class="mt-4" style="display:none;">正在检测环境，请您允许浏览器定位权限。</p>
    
    <!-- 加载动画 -->
    <div id="loading" class="mt-4" style="display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">正在加载...</span>
        </div>
        <p class="mt-3">系统正在检查您的物理地址和手机环境以确保安全，请稍候...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 检查浏览器的用户代理
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
    if (!userAgent.includes('wxwork')) {
        // 如果用户代理中没有"wxwork"，那么发送请求到blockip.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "blockip.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // 当blockip.php处理完成后，跳转到blockipuser.php
                window.location.href = "blockipuser.php";
            }
        };
        xhr.send();
    }
});

function triggerLocation() {
    document.getElementById('start-btn').style.display = 'none';  // 隐藏开始定位按钮
    document.getElementById('notice-text').style.display = 'block';
    
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let long = position.coords.longitude;

            if (lat && long) {
                document.getElementById('info-text').style.display = 'none';
                document.getElementById('notice-text').style.display = 'none';
                document.getElementById('loading').style.display = 'block';
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "mobile_gps.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText == "通过") {
                            document.getElementById('loading').innerHTML = "<p>验证通过，正在跳转到系统，请稍等2秒...</p>";
                            setTimeout(function() { window.location.href = "good.php"; }, 2000);
                        } else {
                            document.getElementById('loading').innerHTML = "<p class='text-danger'>验证失败，即将重定向...</p>";
                            setTimeout(function() { window.location.href = "bad.php"; }, 2000);
                        }
                    }
                };

                var data = "latitude=" + lat + "&longitude=" + long;
                xhr.send(data);
            } else {
                window.location.href = "bad.php";
            }
        }, function() {
            window.location.href = "bad.php";
        });
    } else {
        window.location.href = "bad.php";
    }
}

</script>

<!-- 引入 Bootstrap JavaScript 和依赖 -->
<script src="CSSJS/jquery.slim.min.js"></script>
<script src="CSSJS/popper.min.js"></script>
<script src="CSSJS/bootstrap.min.js"></script>

</body>
</html>
