<?php
session_start();
include("connection.php");

header('Content-Type: application/json');

$order_no = isset($_GET['order_no']) ? $_GET['order_no'] : '';

if (!$order_no) {
    echo json_encode(['status' => 'error', 'message' => '参数错误']);
    exit();
}

// 查询订单状态
$query = "SELECT * FROM orders WHERE order_no = '$order_no'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if ($order) {
    if ($order['status'] == 1) {
        // 已支付
        echo json_encode([
            'status' => 'paid',
            'message' => '支付成功',
            'trade_no' => $order['trade_no'],
            'paid_at' => $order['paid_at']
        ]);
    } else {
        // 未支付
        echo json_encode([
            'status' => 'unpaid',
            'message' => '等待支付'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => '订单不存在'
    ]);
}
?>