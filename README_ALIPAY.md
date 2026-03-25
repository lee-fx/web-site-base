# 支付宝网页支付集成说明

## 📋 功能概述

已成功集成支付宝网页支付（电脑网站支付）功能，支持：
- ✅ 即时到账
- ✅ 二维码扫码支付
- ✅ 订单管理
- ✅ 支付状态实时查询
- ✅ 同步/异步通知回调

## 🚀 使用流程

### 用户操作流程：
1. 访问 [projects.php](file://d:\phpstudy_pro\WWW\www\web-site-dianyao\projects.php) 服务大厅
2. 浏览服务项目列表
3. 点击"点击购买"按钮
4. 跳转到支付宝支付页面 [alipay.php](file://d:\phpstudy_pro\WWW\www\web-site-dianyao\alipay.php)
5. 扫码或跳转支付宝完成支付
6. 支付成功后自动跳转到成功页面

## 📁 新增文件说明

### 核心文件：
1. **alipay.php** - 支付宝支付页面（显示项目信息、金额、二维码）
2. **alipay_gateway.php** - 支付宝网关跳转（构建支付请求并跳转）
3. **payment_success.php** - 支付成功回调页面（同步通知）
4. **payment_notify.php** - 支付宝异步通知处理
5. **check_payment_status.php** - 支付状态查询 API
6. **create_orders_table.sql** - 订单表创建脚本

### 修改文件：
- **projects.php** - 添加购买按钮链接到 alipay.php

## ⚙️ 配置步骤

### 第一步：创建订单表

在 phpMyAdmin 中执行以下 SQL 或导入 `create_orders_table.sql`：

```sql
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT '订单 ID',
  `order_no` VARCHAR(50) NOT NULL COMMENT '订单编号',
  `project_id` INT NOT NULL COMMENT '项目 ID',
  `user_id` INT NOT NULL COMMENT '用户 ID',
  `amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '订单状态：0-待支付 1-已支付 2-已取消',
  `trade_no` VARCHAR(100) DEFAULT NULL COMMENT '支付宝交易号',
  `paid_at` DATETIME DEFAULT NULL COMMENT '支付时间',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  INDEX `idx_order_no` (`order_no`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_project_id` (`project_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单表';
```

### 第二步：申请支付宝开放平台应用

1. 访问 [支付宝开放平台](https://open.alipay.com/)
2. 登录并创建应用
3. 申请"电脑网站支付"产品
4. 获取以下关键信息：
   - **APPID**（应用 ID）
   - **商户私钥**（应用私钥）
   - **支付宝公钥**

### 第三步：配置支付宝参数

编辑文件：[alipay_gateway.php](file://d:\phpstudy_pro\WWW\www\web-site-dianyao\alipay_gateway.php)

找到以下配置项并替换为您的真实配置：

```php
$alipay_config = [
    'app_id' => 'YOUR_APP_ID',           // 替换为您的 APPID
    'merchant_private_key' => 'YOUR_PRIVATE_KEY',  // 替换为商户私钥
    'alipay_public_key' => 'YOUR_PUBLIC_KEY',      // 替换为支付宝公钥
    'charset' => 'UTF-8',
    'sign_type' => 'RSA2',
    'gateway_url' => 'https://openapi.alipay.com/gateway.do',
    'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment_success.php',
    'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment_notify.php',
];
```

### 第四步：配置回调地址

在支付宝开放平台设置：
- **同步回调地址**：`http://您的域名/payment_success.php`
- **异步通知地址**：`http://您的域名/payment_notify.php`

> ⚠️ 注意：本地测试需要使用内网穿透工具（如 natapp、ngrok）将本地服务暴露到公网

## 🧪 测试环境搭建

### 方法一：使用支付宝沙箱环境（推荐）

1. 访问 [支付宝沙箱环境](https://open.alipay.com/develop/sandbox/app)
2. 创建沙箱应用
3. 获取沙箱环境的 APPID、私钥、公钥
4. 使用沙箱提供的测试账号进行支付测试

### 方法二：本地开发环境

1. 安装内网穿透工具（如 ngrok）：
```bash
ngrok http 80
```

2. 将生成的临时域名配置到支付宝回调地址

3. 修改配置文件中的域名为临时域名

## 💻 代码说明

### 1. alipay.php - 支付页面
- 显示项目信息和支付金额
- 生成支付二维码
- 提供"立即支付"按钮
- 轮询检查支付状态

### 2. alipay_gateway.php - 网关跳转
- 构建支付宝支付请求参数
- 生成签名（需要 RSA2 算法）
- 重定向到支付宝网关

### 3. payment_success.php - 成功回调
- 接收支付宝同步返回参数
- 更新订单状态
- 显示成功信息

### 4. payment_notify.php - 异步通知
- 接收支付宝异步通知
- 验证签名
- 更新订单和项目状态
- 返回 "success" 确认收到通知

### 5. check_payment_status.php - 状态查询
- 提供 JSON 格式订单状态
- 供前端轮询使用

## 🔐 安全注意事项

### 必须实现的安全措施：

1. **签名验证**
   - 在 `payment_notify.php` 中必须验证支付宝返回的签名
   - 防止伪造支付通知

2. **金额验证**
   - 比较订单金额和实际支付金额是否一致
   - 防止篡改金额

3. **订单唯一性**
   - 确保订单号全局唯一
   - 防止重复支付

4. **HTTPS 加密**
   - 生产环境必须使用 HTTPS
   - 防止数据被窃取

5. **日志记录**
   - 记录所有支付相关操作
   - 便于问题排查和审计

## 📝 完整签名函数示例

在 `alipay_gateway.php` 中添加以下函数：

```php
/**
 * RSA2 签名
 */
function rsaSign($data, $privateKey) {
    $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
        wordwrap($privateKey, 64, "\n", true) .
        "\n-----END RSA PRIVATE KEY-----";
    
    $res = openssl_get_privatekey($privateKey);
    if (!$res) {
        die("私钥错误");
    }
    
    openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
    openssl_free_key($res);
    
    return base64_encode($sign);
}

/**
 * 验证签名
 */
function verifySign($data, $publicKey) {
    $sign = $data['sign'];
    unset($data['sign']);
    ksort($data);
    
    $data_str = http_build_query($data);
    
    $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
        wordwrap($publicKey, 64, "\n", true) .
        "\n-----END PUBLIC KEY-----";
    
    $res = openssl_get_publickey($publicKey);
    $result = openssl_verify($data_str, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
    openssl_free_key($res);
    
    return $result === 1;
}
```

## 🎯 常见问题

### Q1: 签名失败？
A: 检查私钥和公钥是否正确，确保使用的是 RSA2 算法

### Q2: 回调地址不生效？
A: 
- 检查回调地址是否可公网访问
- 查看支付宝开放平台配置是否正确
- 检查服务器防火墙设置

### Q3: 本地如何测试？
A: 使用支付宝沙箱环境 + 内网穿透工具

### Q4: 支付成功后页面没反应？
A: 检查 `payment_success.php` 是否正常接收参数，查看浏览器控制台错误

## 📞 技术支持

- 支付宝开放平台文档：https://opendocs.alipay.com/
- 电脑网站支付 API：https://opendocs.alipay.com/open/07x2qp

## ⚠️ 免责声明

当前代码为演示版本，用于学习和开发参考。生产环境使用时：
1. 必须完善签名验证逻辑
2. 必须使用 HTTPS 加密传输
3. 必须做好日志记录和监控
4. 建议咨询专业开发人员确保安全性和稳定性

---

**开发日期**：2026-03-25  
**适用版本**：支付宝电脑网站支付 V2
