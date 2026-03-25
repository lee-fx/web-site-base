<?php
session_start();
include("connection.php");

// 检查登录状态
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

// 获取参数
$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order_no = isset($_GET['order_no']) ? $_GET['order_no'] : '';

if (!$project_id || !$order_no) {
    die("参数错误");
}

// 查询项目信息
$query = "SELECT * FROM projects WHERE id = $project_id";
$result = mysqli_query($conn, $query);
$project = mysqli_fetch_assoc($result);

if (!$project) {
    die("项目不存在");
}

// ==================== 支付宝接口配置 ====================
// 注意：以下配置需要替换为您在支付宝开放平台申请的真实配置
$alipay_config = [
    'app_id' => '2021006140637693',           // 支付宝应用 ID（必填）
    'merchant_private_key' => 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDgnH/M2bsEeO9mvirSx4WCApk+7A+0+ALkMaDEQzdwAY/DtOWqrwvAR9OAtEmp2FFp6XbdHVh8XdtmD9nTSsji5BI4YHxnPwoc8mxphfOUzhveXjh3lFmSqZtDrS2fOdcP/HbQmaSFSaMbHT4Sal/c9TVpf79fKEGk756z8D9FTjTdwBF+ErpH05op+046+EZUxEXRvCpzG6ipTNS+IXAWW7TLQcKW9dJ3e+vWZ+QgESbwQLzEO9ISw+ZAMfd0RGj4V3eVjFo9hyyGmCXk2JkBNWUzmlKXHWWBCXOZKEZIzYrYru5rYT2wixMwJnYw4bn8bx4YHTiE9nl6HLtTY+37AgMBAAECggEAfdvT1QLQQ7JMW6MFtfRtQCuamWKHbp1maV3Ruwf7IX0OhNhxtRG/IPBeeIkNmemHvTRAEaim85FYJjmNklMee9zWxNZz/xqY3R2OWBbiJajUfucG9yKeiosVYHfCz60GcIPERCFDyhg4GDJrnMAUlWillEcsAfM5zWUj5aoimf8qzNCMoFhRxyJdqd4HUaewPUXZMGop1rcdL3zTSOICRaGVmpbpkfFEf9POsA/6NOTdj9oMBGpHguZwmTxr0H1SA1xSaERkAYauLqH4AvD3c5WwT7Wk4hGlwdo5cD9/Qy1jM7Iqu5AgayMKihcKpDOEdso0MTe7FWN9OHY2LUJtWQKBgQD3ZPtKQoCsj0p9ybMbiJkPT4SK7whqvAFzXOghDrEFlJd+q18oSA10oYnM3lMyEYIKfUs9YcTHISCxcx+RKAuFIbxzVTDFXAstwURb3QmTwC29GgRRExbgNp1YSkin78eoityZ3h3Cs9d3+UhxuUfRhsbhNCYV+Hd/nqxCtH9b5wKBgQDobKLxw6BChhk/M77qKiKxR7JqXEabghZhumIothn5JS8lhm78Xy+0rl5g8c1dgx2OOfrYaNBJkjJI5qmk9/fuASCAQZ70lnTdpFT9nglEs4Iq6PZ5lo0M4dsVrdr1UlG4VJLz9H4kdbsTGj8uwJLbCb8qe9wAVXK5OK6O8iM6zQKBgAz9R1hpz3m/+EhMe9P5QxgTs8DbthRslKLrAOEjdVX2rFGhS9jAl7xE8whHsBST6MEGPRoW9Q8DhUTr539mm9w8njlsAqiKIaggWqd8HpGcaIpiQFHFb9EL7Q0CsXfn1hLaX6TQjb14Vdrdwtj9wnCU5AJVC57hsdyIh0DlWz6HAoGAOlJQTPQ2uZqmqSpV5LJwBCYmifPfr9Pjw+AeE3Mmb1M6UmM6ZE+KS/9ZJWBnlOh6uR6paDZc1ak+vvZKaV/jsbBMtIJC3cMHTjqXLRDD6J5WsmfEuVvixvHBpN38TIS8htGKEncuU/3K1I9IfXXLI/V9J67u1JKceAfWHX57Ap0CgYAI0NTI3Qe0e5k8btEBMYsIzOSz73nDFrahBVxvUqpe5+AobT5bgNrnU9mTS5akjqiziUzEtRwlxkMOKzeIYGsTzR4xhX4QZjtLHgVbSUtuoVidLetVVnZzoE7hwAIA7aFT1MKBV2HZJqVdUl4h20NNCOg2F5YbhIl4AwUwqbtVSw==',  // 商户私钥（必填）
    'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAo0uBXxzmc+frS+rHWO2GFzzana9RTjN86ngJkO6vCLa+r8375IvjKRKMh4dWWQ10CfgRlLr7AqI8dSpXTV8i+VbY1FsrLgCplUzNKDCmcELgRlDXFJq+aK42Gda75xUHjP7zXQ6QMTJ1FjUpUY66pnSOxcD7dBepjRK9JMYb/oys84AEceIie18I+Oh26irCaXgzcwsLVggNCqbOytHOPaCJcoGTLAuzOGDqhmqkUm2gv9btGGVi4ReBJGJITlBIVtRmAWSbKaxowcxdcOTIUB6qs+xtWv1QY02YiU8TZGrmKZuTcpljzkqqWyYlSDSDuhWpyXzs/GKggFK1mP9tVQIDAQAB',      // 支付宝公钥（必填）
    'charset' => 'UTF-8',
    'sign_type' => 'RSA2',
    'gateway_url' => 'https://openapi.alipay.com/gateway.do',
    'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment_success.php',  // 同步回调地址
    'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment_notify.php',   // 异步通知地址
];

// 构建支付请求参数
$request_params = [
    'method' => 'alipay.trade.page.pay',  // 电脑网站支付
    'charset' => $alipay_config['charset'],
    'sign_type' => $alipay_config['sign_type'],
    'timestamp' => date('Y-m-d H:i:s'),
    'version' => '1.0',
    'app_id' => $alipay_config['app_id'],
    'biz_content' => json_encode([
        'out_trade_no' => $order_no,           // 商户订单号
        'product_code' => 'FAST_INSTANT_TRADE_PAY',  // 产品码
        'total_amount' => number_format(floatval($project['price']), 2),  // 订单金额
        'subject' => $project['title'],        // 订单标题
        'return_url' => $alipay_config['return_url'],
        'notify_url' => $alipay_config['notify_url'],
    ]),
];

// 生成签名（实际使用时需要使用 RSA2 签名算法）
// 这里使用简化版本，实际需要完整的签名函数
ksort($request_params);
$sign_string = http_build_query($request_params);
// 使用商户私钥进行 RSA2 签名
$request_params['sign'] = rsaSign($sign_string, $alipay_config['merchant_private_key']);

// 为了演示，这里直接构建跳转 URL
// $pay_url = $alipay_config['gateway_url'] . '?' . http_build_query($request_params);

// 重定向到支付宝支付页面
header("localhost: " . $pay_url);
exit();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>正在跳转到支付宝...</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #1677ff 0%, #0050b3 100%);
            color: white;
            font-family: 'Microsoft YaHei', sans-serif;
        }
        .loading {
            text-align: center;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        p {
            margin: 10px 0;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="loading">
        <div class="spinner"></div>
        <h2>正在跳转到支付宝支付页面...</h2>
        <p>请稍候，系统正在处理您的请求</p>
        <p style="margin-top: 30px; font-size: 0.9rem;">
            如果没有自动跳转，请<a href="<?php echo htmlspecialchars($pay_url); ?>" style="color: white; text-decoration: underline;">点击此处</a>手动跳转
        </p>
    </div>
    
    <script>
        // 3 秒后自动跳转
        setTimeout(function() {
            window.location.href = '<?php echo addslashes($pay_url); ?>';
        }, 1000);
    </script>
</body>
</html>