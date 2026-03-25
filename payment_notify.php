<?php
include("connection.php");

// 获取支付宝异步通知参数
$notify_data = $_POST;

// 验证签名（必须验证）
// $verify_result = verifySign($notify_data, $alipay_config['alipay_public_key']);
// if (!$verify_result) {
//     echo "fail";
//     exit();
// }

// 获取关键参数
$out_trade_no = isset($notify_data['out_trade_no']) ? $notify_data['out_trade_no'] : '';
$trade_no = isset($notify_data['trade_no']) ? $notify_data['trade_no'] : '';
$trade_status = isset($notify_data['trade_status']) ? $notify_data['trade_status'] : '';
$total_amount = isset($notify_data['total_amount']) ? $notify_data['total_amount'] : '';

// 只处理交易成功的通知
if ($trade_status == 'TRADE_SUCCESS' || $trade_status == 'TRADE_FINISHED') {
    // 查询订单
    $order_query = "SELECT * FROM orders WHERE order_no = '$out_trade_no' AND status = 0";
    $order_result = mysqli_query($conn, $order_query);
    
    if (mysqli_num_rows($order_result) > 0) {
        $order = mysqli_fetch_assoc($order_result);
        
        // 更新订单状态
        $update_query = "UPDATE orders 
                        SET status = 1, 
                            trade_no = '$trade_no', 
                            paid_at = NOW() 
                        WHERE order_no = '$out_trade_no'";
        mysqli_query($conn, $update_query);
        
        // 更新项目状态为已审核通过
        $project_id = $order['project_id'];
        mysqli_query($conn, "UPDATE projects SET status = 1 WHERE id = $project_id");
        
        // 这里可以添加其他业务逻辑，如发送通知邮件、短信等
        
        // 记录支付成功日志
        error_log("Payment Success - Order: $out_trade_no, Trade No: $trade_no, Amount: $total_amount");
    }
}

// 返回 success 给支付宝，表示已收到通知
echo "success";
?>