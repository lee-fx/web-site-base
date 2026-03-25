<?php
session_start();
include("connection.php");

// 检查登录状态
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

// 获取项目 ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("参数错误");
}

$project_id = intval($_GET['id']);

// 查询项目信息
$query = "SELECT * FROM projects WHERE id = $project_id";
$result = mysqli_query($conn, $query);
$project = mysqli_fetch_assoc($result);

if (!$project) {
    die("项目不存在");
}

// 生成订单号
$order_no = 'ALIPAY' . date('YmdHis') . rand(1000, 9999);

// 保存订单到数据库（可选，用于后续订单管理）
$order_query = "INSERT INTO orders (order_no, project_id, user_id, amount, status, created_at) 
                VALUES ('$order_no', $project_id, " . $_SESSION['id'] . ", " . floatval($project['price']) . ", 0, NOW())";
mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支付宝支付 - <?php echo htmlspecialchars($project['title']); ?></title>
    
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
        
        .payment-card {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #1677ff 0%, #0050b3 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .payment-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }
        
        .payment-body {
            padding: 40px;
        }
        
        .project-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .project-info h4 {
            margin: 0 0 15px 0;
            color: #333;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #666;
        }
        
        .amount-display {
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .amount-display .label {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .amount-display .amount {
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .pay-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1677ff 0%, #0050b3 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .pay-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(22, 119, 255, 0.4);
        }
        
        .pay-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .cancel-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }
        
        .cancel-btn:hover {
            color: #333;
        }
        
        .qr-code-container {
            text-align: center;
            padding: 20px;
            background: white;
            border: 2px dashed #1677ff;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #1677ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .success-icon {
            color: #52c41a;
            font-size: 3rem;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="payment-card">
        <div class="payment-header">
            <h2><i class="bi bi-credit-card"></i> 支付宝支付</h2>
        </div>
        
        <div class="payment-body">
            <!-- 项目信息 -->
            <div class="project-info">
                <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                <div class="info-row">
                    <span>订单编号：</span>
                    <span><?php echo $order_no; ?></span>
                </div>
                <div class="info-row">
                    <span>创建时间：</span>
                    <span><?php echo date('Y-m-d H:i:s'); ?></span>
                </div>
            </div>
            
            <!-- 金额显示 -->
            <div class="amount-display">
                <div class="label">支付金额</div>
                <div class="amount">¥<?php echo number_format(floatval($project['price']), 2); ?></div>
            </div>
            
            <!-- 二维码支付区域 -->
            <div class="qr-code-container" id="qrCodeContainer">
                <div id="qrCodeContent">
                    <div class="loading-spinner"></div>
                    <p style="margin-top: 15px; color: #666;">正在生成支付二维码...</p>
                </div>
            </div>
            
            <!-- 立即支付按钮 -->
            <button class="pay-btn" onclick="initiatePayment()" id="payBtn">
                <i class="bi bi-alipay"></i> 立即支付
            </button>
            
            <a href="projects.php" class="cancel-btn">取消支付</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script>
        // 模拟支付宝支付链接（实际使用时需要替换为真实的支付宝 API 接口）
        const alipayConfig = {
            appId: '2021006140637693', // 替换为您的支付宝应用 ID
            returnUrl: window.location.origin + '/payment_success.php',
            notifyUrl: window.location.origin + '/payment_notify.php'
        };
        
        // 生成支付二维码
        function generateQRCode(paymentUrl) {
            const container = document.getElementById('qrCodeContent');
            QRCode.toCanvas(paymentUrl, {
                width: 200,
                margin: 2
            }, function (error, canvas) {
                if (error) {
                    container.innerHTML = '<p style="color: red;">二维码生成失败</p>';
                    return;
                }
                container.innerHTML = '';
                container.appendChild(canvas);
                
                // 添加提示文字
                const tip = document.createElement('p');
                tip.style.marginTop = '15px';
                tip.style.color = '#666';
                tip.style.fontSize = '0.9rem';
                tip.innerHTML = '使用支付宝扫码<br>即时到账';
                container.appendChild(tip);
            });
        }
        
        // 初始化支付
        function initiatePayment() {
            const payBtn = document.getElementById('payBtn');
            payBtn.disabled = true;
            payBtn.innerHTML = '<div class="loading-spinner" style="display:inline-block;width:20px;height:20px;border-width:3px;"></div> 正在跳转...';
            
            // 模拟跳转到支付宝支付页面
            // 实际使用时，这里应该调用后端 API 获取支付宝支付链接
            setTimeout(() => {
                // 方式一：直接跳转到支付宝网关（需要后端配合）
                window.location.href = 'alipay_gateway.php?id=<?php echo $project_id; ?>&order_no=<?php echo $order_no; ?>';
                
                // 方式二：如果是测试环境，显示成功提示
                // showSuccessMessage();
            }, 1000);
        }
        
        // 显示成功消息（测试用）
        function showSuccessMessage() {
            alert('支付成功！\n\n这是演示模式，实际项目中需要对接支付宝官方 API。');
            window.location.href = 'projects.php?payment=success';
        }
        
        // 页面加载时生成二维码
        window.addEventListener('DOMContentLoaded', function() {
            // 构建支付宝支付链接（实际应调用后端 API 获取真实链接）
            // 这里直接跳转到网关页面生成正式链接
            const gatewayUrl = `alipay_gateway.php?id=<?php echo $project_id; ?>&order_no=<?php echo $order_no; ?>`;
            
            // 为了演示，生成一个示例二维码
            // 实际应该调用后端接口获取真实的支付 URL
            generateQRCode(gatewayUrl);
            
            // 监听支付状态（轮询）
            setInterval(checkPaymentStatus, 3000);
        });
        
        // 检查支付状态
        function checkPaymentStatus() {
            fetch(`check_payment_status.php?order_no=<?php echo $order_no; ?>`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid') {
                        alert('支付成功！');
                        window.location.href = 'projects.php?payment=success';
                    }
                })
                .catch(error => console.log('检查支付状态失败:', error));
        }
    </script>
</body>

</html>