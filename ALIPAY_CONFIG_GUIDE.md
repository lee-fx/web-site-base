# 支付宝支付配置完整指南

## 🔧 扫码支付错误解决方案

### 问题：缺少时间戳参数

**原因：**
- 支付宝接口要求必须传递 `timestamp` 参数
- 必须进行 RSA2 签名验证
- 参数格式必须符合支付宝规范

**已修复内容：**
✅ 添加了完整的 `timestamp` 参数（格式：`YYYY-MM-DD HH:MM:SS`）
✅ 实现了 RSA2 签名函数
✅ 规范了参数排序和签名生成流程

---

## 📋 第一步：申请支付宝沙箱环境（推荐新手）

### 1. 访问沙箱环境
打开浏览器访问：https://open.alipay.com/develop/sandbox/app

### 2. 登录支付宝账号
- 使用您的支付宝账号登录
- 如果没有账号，需要先注册

### 3. 创建沙箱应用
1. 点击"沙箱应用"标签
2. 点击"添加应用"或"创建应用"
3. 选择"电脑网站支付"功能
4. 填写应用信息并提交

### 4. 获取关键配置信息
创建成功后，您会看到以下重要信息：

```
应用 APPID: 2021xxxxxxxxxxxxx
网关地址：https://openapi-sandbox.dl.alipaydev.com/gateway.do
应用私钥：MIIEvQIBADANBgkqhkiG9w0BAQEFAASC...
应用公钥：MIIEvQIBADANBgkqhkiG9w0BAQEFAASC...
支付宝公钥：MIIEvQIBADANBgkqhkiG9w0BAQEFAASC...
```

### 5. 下载沙箱版支付宝
- 在沙箱页面下载"沙箱版支付宝"APP
- 用于扫码支付测试

### 6. 获取测试账号
沙箱环境会提供两个测试账号：
- **买家账号**：用于扫码支付
- **卖家账号**：用于收款（您的应用）

---

## ⚙️ 第二步：配置项目文件

### 修改 alipay_gateway.php

打开文件：[alipay_gateway.php](file://d:\phpstudy_pro\WWW\www\web-site-dianyao\alipay_gateway.php)

找到第 28-38 行的配置数组：

```php
$alipay_config = [
    'app_id' => 'YOUR_APP_ID',           // ← 替换为您的 APPID
    'merchant_private_key' => 'YOUR_PRIVATE_KEY',  // ← 替换为应用私钥
    'alipay_public_key' => 'YOUR_PUBLIC_KEY',      // ← 替换为支付宝公钥
    'charset' => 'UTF-8',
    'sign_type' => 'RSA2',
    'gateway_url' => 'https://openapi.alipay.com/gateway.do',
    'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment_success.php',
    'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment_notify.php',
];
```

### 配置示例（沙箱环境）：

```php
$alipay_config = [
    'app_id' => '2021567890123456',  // 替换为您的沙箱 APPID
    'merchant_private_key' => 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC...',  // 应用私钥（不带换行）
    'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAr...',  // 支付宝公钥
    'charset' => 'UTF-8',
    'sign_type' => 'RSA2',
    'gateway_url' => 'https://openapi-sandbox.dl.alipaydev.com/gateway.do',  // 沙箱网关
    'return_url' => 'http://localhost/web-site-dianyao/payment_success.php',
    'notify_url' => 'http://localhost/web-site-dianyao/payment_notify.php',
];
```

### ⚠️ 注意事项：

1. **私钥格式处理**
   - 如果私钥包含换行符，需要移除或使用 `wordwrap()` 函数格式化
   - 代码中已包含格式化逻辑，直接粘贴即可

2. **网关地址区分**
   - 沙箱环境：`https://openapi-sandbox.dl.alipaydev.com/gateway.do`
   - 正式环境：`https://openapi.alipay.com/gateway.do`

3. **回调地址配置**
   - 本地测试：使用 `http://localhost/项目路径/文件名.php`
   - 生产环境：使用您的域名 `https://您的域名/文件名.php`

---

## 🧪 第三步：本地测试流程

### 1. 确保数据库已准备

在 phpMyAdmin 中确认以下表存在：
- ✅ `projects` 表（已有数据）
- ✅ `orders` 表（运行 `create_orders_table.sql` 创建）

### 2. 启动本地服务器

确保 phpstudy_pro 已启动：
- Apache 服务运行中
- MySQL 服务运行中

### 3. 访问服务大厅

浏览器访问：`http://localhost/web-site-dianyao/projects.php`

### 4. 测试支付流程

1. 点击任意服务的"点击购买"按钮
2. 跳转到支付页面 [alipay.php](file://d:\phpstudy_pro\WWW\www\web-site-dianyao\alipay.php)
3. 查看是否显示项目信息和金额
4. 点击"立即支付"按钮
5. 应该跳转到支付宝网关（或显示配置错误）

### 5. 常见错误排查

#### 错误 1："缺少时间戳参数"
**原因：** timestamp 参数未正确传递  
**解决：** 已在代码中修复，确保 `date('Y-m-d H:i:s')` 格式正确

#### 错误 2："签名失败"或"非法签名"
**原因：** 
- 私钥配置错误
- 签名算法不匹配
- 参数顺序问题

**解决：**
```php
// 检查私钥是否正确（去掉首尾的 -----BEGIN...-----）
$merchant_private_key = '您的私钥（不含头尾标记）';

// 确保 sign_type 为 RSA2
'sign_type' => 'RSA2',

// 确保 charset 为 UTF-8
'charset' => 'UTF-8',
```

#### 错误 3："无效的 APPID"
**原因：** APPID 配置错误或使用错误的网关  
**解决：** 
- 检查 APPID 是否与沙箱/正式环境匹配
- 沙箱应用必须使用沙箱网关

#### 错误 4："回调地址无效"
**原因：** 回调地址未在支付宝平台配置  
**解决：**
- 登录支付宝开放平台
- 在应用设置中添加回调地址
- 确保与代码中的 `return_url` 和 `notify_url` 一致

---

## 🌐 第四步：内网穿透配置（本地调试回调）

支付宝异步通知需要公网可访问的地址。

### 使用 natapp（推荐）

1. **下载安装 natapp**
   ```bash
   # Windows 用户下载 natapp.exe
   # https://natapp.cn/tunnel/download
   ```

2. **注册并获取 authtoken**
   - 访问 https://natapp.cn/
   - 注册账号
   - 购买免费隧道
   - 获取 authtoken

3. **配置 config.ini**
   ```ini
   [tunnel1]
   authtoken=您的 authtoken
   port=80
   ```

4. **启动 natapp**
   ```bash
   natapp -config=config.ini
   ```

5. **获取临时域名**
   - 启动后会显示类似：`http://abc123.natappfree.cc`
   - 将此域名配置到支付宝回调地址

6. **修改代码中的回调地址**
   ```php
   'return_url' => 'http://abc123.natappfree.cc/payment_success.php',
   'notify_url' => 'http://abc123.natappfree.cc/payment_notify.php',
   ```

---

## 📝 第五步：完整测试清单

### ✅ 环境准备
- [ ] phpstudy_pro 已启动
- [ ] MySQL 服务正常
- [ ] `orders` 表已创建
- [ ] `projects` 表有测试数据

### ✅ 支付宝配置
- [ ] 已申请沙箱应用
- [ ] APPID 已复制到配置文件
- [ ] 应用私钥已复制
- [ ] 支付宝公钥已复制
- [ ] 网关地址正确（沙箱/正式）

### ✅ 回调地址
- [ ] return_url 可访问
- [ ] notify_url 可访问
- [ ] 地址已在支付宝平台配置

### ✅ 功能测试
- [ ] 能正常访问 projects.php
- [ ] 点击购买跳转到 alipay.php
- [ ] 显示正确的项目信息和金额
- [ ] 点击"立即支付"能跳转
- [ ] 沙箱支付宝能打开
- [ ] 使用买家账号能登录
- [ ] 扫码后能完成支付
- [ ] 支付成功跳转到 success.php
- [ ] 数据库中 orders 表状态更新

---

## 🔐 生产环境部署注意事项

### 1. 切换到正式环境

```php
// 修改网关地址
'gateway_url' => 'https://openapi.alipay.com/gateway.do',

// 使用正式环境的密钥
'app_id' => '正式环境 APPID',
'merchant_private_key' => '正式环境私钥',
'alipay_public_key' => '正式环境公钥',
```

### 2. 配置 HTTPS

生产环境必须使用 HTTPS：
```php
'return_url' => 'https://您的域名/payment_success.php',
'notify_url' => 'https://您的域名/payment_notify.php',
```

### 3. 完善签名验证

在 [payment_notify.php](file://d:\phpstudy_pro\WWW\www\web-site-dianyao\payment_notify.php) 中添加：

```php
/**
 * 验证支付宝返回的签名
 */
function verifySign($data, $publicKey) {
    $sign = $data['sign'];
    unset($data['sign']);
    
    ksort($data);
    $stringToBeSigned = "";
    $i = 0;
    foreach ($data as $k => $v) {
        if ("@" != substr($k, 0, 1) && '' !== $v && !is_null($v)) {
            if ($i == 0) {
                $stringToBeSigned .= "$k=$v";
            } else {
                $stringToBeSigned .= "&$k=$v";
            }
            $i++;
        }
    }
    
    $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
        wordwrap($publicKey, 64, "\n", true) .
        "\n-----END PUBLIC KEY-----";
    
    $res = openssl_get_publickey($publicKey);
    $result = openssl_verify($stringToBeSigned, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
    openssl_free_key($res);
    
    return $result === 1;
}

// 使用示例
if (!verifySign($_POST, $alipay_config['alipay_public_key'])) {
    echo "fail";
    exit();
}
```

---

## 📞 技术支持资源

- **支付宝开放平台文档**：https://opendocs.alipay.com/
- **电脑网站支付 API**：https://opendocs.alipay.com/open/07x2qp
- **沙箱环境**：https://open.alipay.com/develop/sandbox/app
- **常见问题**：https://opensupport.alipay.com/

---

## 🎯 快速验证代码是否生效

### 测试步骤：

1. **不配置密钥，直接测试**
   - 访问 projects.php
   - 点击购买
   - 应该看到"私钥配置错误"提示（说明代码已执行）

2. **配置测试密钥**
   - 填入沙箱 APPID 和密钥
   - 再次点击购买
   - 应该跳转到支付宝页面

3. **完成支付测试**
   - 使用沙箱买家账号扫码
   - 输入密码支付
   - 应该跳转到 payment_success.php

---

**更新日期**：2026-03-25  
**适用版本**：支付宝电脑网站支付 V2（含沙箱环境）
