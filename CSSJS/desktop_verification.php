<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安全身份验证系统</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .verification-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .verification-text {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="verification-container">
    <div class="verification-text">当前系统正对您的浏览器环境进行高级安全验证，请耐心等待。</div>
    <div class="loader"></div>
</div>

<script>
    function fetchData(url, callback) {
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Configure the request
        xhr.open('GET', url, true);

        // Set up the callback for when the request completes
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                // Success - pass the result to the callback
                callback(null, JSON.parse(xhr.responseText));
            } else {
                // Error - pass the error to the callback
                callback(new Error('Error fetching data'), null);
            }
        };

        // Set up the callback for when there's an error
        xhr.onerror = function() {
            callback(new Error('Error fetching data'), null);
        };

        // Send the request
        xhr.send();
    }
            //token获取与验证
    function generateAndVerifyToken() {
        // Fetch token directly from sys.ganlusz.com
        fetchData('https://www.com/verification/api.php?action=generateToken', function(error, data) {
            if (error || data.status !== "success" || !data.token) {
                window.location.href = 'bad.php';
                return;
            }

            var generatedToken = data.token;
            
            // Verify token
            fetchData('https://www.com/verification/api.php?action=verifyToken&token=' + generatedToken, function(verifyError, verifyData) {
                if (verifyError || !verifyData.valid) {
                    window.location.href = 'bad.php';
                } else {
                    window.location.href = 'good.php';
                }
            });
        });
    }

    // Call the function to generate and verify the token when the page loads
    generateAndVerifyToken();
</script>

</body>
</html>
