<?php
session_start();
include("connection.php");

// 获取支付宝返回的参数
$out_trade_no = isset($_GET['out_trade_no']) ? $_GET['out_trade_no'] : '';
$trade_no = isset($_GET['trade_no']) ? $_GET['trade_no'] : '';
$total_amount = isset($_GET['total_amount']) ? $_GET['total_amount'] : '';

// 验证签名（实际使用时必须验证）
// $verify_result = verifySign($_GET, $alipay_config['alipay_public_key']);

// 更新订单状态
if ($out_trade_no) {
    // 查询订单信息
    $order_query = "SELECT * FROM orders WHERE order_no = '$out_trade_no'";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);
    
    if ($order) {
        // 更新订单状态为已支付
        $update_query = "UPDATE orders SET status = 1, trade_no = '$trade_no', paid_at = NOW() WHERE order_no = '$out_trade_no'";
        mysqli_query($conn, $update_query);
        
        // 更新项目状态（如果需要）
        $project_id = $order['project_id'];
        mysqli_query($conn, "UPDATE projects SET status = 1 WHERE id = $project_id");
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支付成功</title>
    
    <link href="resource/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet"
        href="resource/fonts/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', 'Microsoft YaHei', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-card {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            padding: 50px 30px;
        }
        
        .success-icon {
            color: #52c41a;
            font-size: 5rem;
            margin-bottom: 20px;
        }
        
        .success-title {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 15px;
        }
        
        .success-message {
            color: #666;
            font-size: 1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .order-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #666;
        }
        
        .info-label {
            font-weight: 600;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1677ff 0%, #0050b3 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-primary:hover, .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: white;
        }
    </style>
</head>

<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="bi bi-check-circle"></i>
        </div>
        
        <h1 class="success-title">支付成功！</h1>
        <p class="success-message">
            恭喜您，已成功购买该服务项目<br>
            我们将尽快为您安排相关服务
        </p>
        
        <?php if ($order): ?>
        <div class="order-info">
            <div class="info-row">
                <span class="info-label">订单编号：</span>
                <span><?php echo htmlspecialchars($out_trade_no); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">支付金额：</span>
                <span style="color: #ff6b6b; font-weight: 600;">¥<?php echo number_format(floatval($total_amount), 2); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">交易流水号：</span>
                <span><?php echo htmlspecialchars($trade_no); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">支付时间：</span>
                <span><?php echo date('Y-m-d H:i:s'); ?></span>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="btn-group">
            <a href="projects.php" class="btn-primary">
                <i class="bi bi-grid"></i> 返回服务大厅
            </a>
            <a href="home.php" class="btn-secondary">
                <i class="bi bi-house"></i> 返回首页
            </a>
        </div>
    </div>
    
    <script>
        // 3 秒后自动跳转到服务大厅
        setTimeout(function() {
            window.location.href = 'projects.php';
        }, 5000);
    </script>
</body>

</html>