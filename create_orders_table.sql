-- 创建订单表
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

-- 验证表是否创建成功
DESCRIBE orders;
