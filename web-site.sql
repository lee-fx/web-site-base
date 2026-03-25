/*
 Navicat Premium Data Transfer

 Source Server         : local_happy_test
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : web-site

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 25/03/2026 16:05:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for contact
-- ----------------------------
DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of contact
-- ----------------------------
INSERT INTO `contact` VALUES (1, 'Ajay', 'ajay@gmail.com', 'qdeeas', 'dsgffhgjhmhjm');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单 ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号',
  `project_id` int(11) NOT NULL COMMENT '项目 ID',
  `user_id` int(11) NOT NULL COMMENT '用户 ID',
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '订单金额',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '订单状态：0-待支付 1-已支付 2-已取消',
  `trade_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '支付宝交易号',
  `paid_at` datetime(0) DEFAULT NULL COMMENT '支付时间',
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_order_no`(`order_no`) USING BTREE,
  INDEX `idx_user_id`(`user_id`) USING BTREE,
  INDEX `idx_project_id`(`project_id`) USING BTREE,
  INDEX `idx_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (1, 'ALIPAY202603251429176876', 15, 19, 2.00, 0, NULL, NULL, '2026-03-25 14:29:17', '2026-03-25 14:29:17');
INSERT INTO `orders` VALUES (2, 'ALIPAY202603251454293736', 13, 19, 77.00, 0, NULL, NULL, '2026-03-25 14:54:29', '2026-03-25 14:54:29');
INSERT INTO `orders` VALUES (3, 'ALIPAY202603251537286062', 15, 19, 2.00, 0, NULL, NULL, '2026-03-25 15:37:28', '2026-03-25 15:37:28');
INSERT INTO `orders` VALUES (4, 'ALIPAY202603251538335006', 15, 19, 2.00, 0, NULL, NULL, '2026-03-25 15:38:33', '2026-03-25 15:38:33');
INSERT INTO `orders` VALUES (5, 'ALIPAY202603251538599950', 15, 19, 2.00, 0, NULL, NULL, '2026-03-25 15:38:59', '2026-03-25 15:38:59');
INSERT INTO `orders` VALUES (6, 'ALIPAY202603251539306537', 15, 19, 0.10, 0, NULL, NULL, '2026-03-25 15:39:30', '2026-03-25 15:39:30');
INSERT INTO `orders` VALUES (7, 'ALIPAY202603251539428523', 15, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:39:42', '2026-03-25 15:39:42');
INSERT INTO `orders` VALUES (8, 'ALIPAY202603251541202340', 15, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:41:20', '2026-03-25 15:41:20');
INSERT INTO `orders` VALUES (9, 'ALIPAY202603251541381779', 13, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:41:38', '2026-03-25 15:41:38');
INSERT INTO `orders` VALUES (10, 'ALIPAY202603251542098039', 13, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:42:09', '2026-03-25 15:42:09');
INSERT INTO `orders` VALUES (11, 'ALIPAY202603251542571261', 13, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:42:57', '2026-03-25 15:42:57');
INSERT INTO `orders` VALUES (12, 'ALIPAY202603251543417897', 13, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:43:41', '2026-03-25 15:43:41');
INSERT INTO `orders` VALUES (13, 'ALIPAY202603251543584106', 13, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:43:58', '2026-03-25 15:43:58');
INSERT INTO `orders` VALUES (14, 'ALIPAY202603251544116987', 12, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:44:11', '2026-03-25 15:44:11');
INSERT INTO `orders` VALUES (15, 'ALIPAY202603251545433625', 12, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:45:43', '2026-03-25 15:45:43');
INSERT INTO `orders` VALUES (16, 'ALIPAY202603251545469357', 12, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:45:46', '2026-03-25 15:45:46');
INSERT INTO `orders` VALUES (17, 'ALIPAY202603251547057715', 12, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:47:05', '2026-03-25 15:47:05');
INSERT INTO `orders` VALUES (18, 'ALIPAY202603251547165689', 12, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:47:16', '2026-03-25 15:47:16');
INSERT INTO `orders` VALUES (19, 'ALIPAY202603251550253520', 12, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:50:25', '2026-03-25 15:50:25');
INSERT INTO `orders` VALUES (20, 'ALIPAY202603251551532609', 12, 19, 0.01, 0, NULL, NULL, '2026-03-25 15:51:53', '2026-03-25 15:51:53');

-- ----------------------------
-- Table structure for projects
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT 0,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 审核中 1 审核成功 2 审核未通过',
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of projects
-- ----------------------------
INSERT INTO `projects` VALUES (1, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (2, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (3, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (4, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (5, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (6, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (7, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (8, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (9, '1', 'resource/images/project1.jpg', '0.01', 1, NULL, 0, '2026-03-24 17:42:09');
INSERT INTO `projects` VALUES (10, '11111111', 'images/project1.jpg', '0.01', 19, '我jsfiojweoifjwoiefjskjfsd', 0, '2026-03-25 10:44:48');
INSERT INTO `projects` VALUES (11, 'hahah', 'images/project1.jpg', '0.01', 19, '', 0, '2026-03-25 10:54:58');
INSERT INTO `projects` VALUES (12, '2323', 'images/project1.jpg', '0.01', 19, '', 0, '2026-03-25 10:55:32');
INSERT INTO `projects` VALUES (13, '777', 'images/project1.jpg', '0.01', 19, '', 0, '2026-03-25 11:05:46');
INSERT INTO `projects` VALUES (15, 'ff', 'images/project3.jpg', '0.01', 19, '', 0, '2026-03-25 11:10:19');

-- ----------------------------
-- Table structure for user_project
-- ----------------------------
DROP TABLE IF EXISTS `user_project`;
CREATE TABLE `user_project`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `money` decimal(11, 2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Ajay Kumar', 'ajay@gmail.com', '12345', 0.00);
INSERT INTO `users` VALUES (2, 'Amit', 'amit@gmail.com', '1234', 0.00);
INSERT INTO `users` VALUES (3, 'Keerti Panwar', 'keerti@gmail.com', '$2y$10$g.xv9BS7DZbZ0KT/.fkGouuD8duIUWY2lDntZ7ZXDDUW6h09ZDiqe', 0.00);
INSERT INTO `users` VALUES (4, 'Ankita', 'ankita@gmail.com', '$2y$10$ZuWPf98dGPFogVM8MoKGxOVZ4v1mXD.WrJQ7rwfvdYLWMpyEyRhtK', 0.00);
INSERT INTO `users` VALUES (5, 'Keerti Panwar', 'keerti1234@gmail.com', '$2y$10$PL6oQH71xCh3F3BALBuVYu6SLn2AVQ41o.i5vi2LosRIWEh1H.0Zi', 0.00);
INSERT INTO `users` VALUES (6, 'ajay@gmail.com1', 'ajay@gmail.com1', '$2y$10$/IcoapYDWHsu8ymqvIA6y.AfqJOlXtoac/mgai6vgQyVMB75ebrYi', 0.00);
INSERT INTO `users` VALUES (7, 'mf777@cnsd', 'mf777@cnsd', 'mf777@cnsd', 0.00);
INSERT INTO `users` VALUES (8, 'mf777@cnsd1', 'mf777@cnsd1', 'mf777@cnsd1', 0.00);
INSERT INTO `users` VALUES (9, 'ajay@gmail.com143', 'ajay@gmail.com143', '$2y$10$tnQG4xfTQG1CpxO3iPa75.czQF4sNzLgrciLhhoNlUKUOPGvTfWJG', 0.00);
INSERT INTO `users` VALUES (10, 'ajay@gmail.com1433', 'ajay@gmail.com1433', '$2y$10$k12fFqGmpJEQ8WOupywOr.2dogP8lLwxayATm8PorcfA7dUJ2k8rq', 0.00);
INSERT INTO `users` VALUES (11, 'ajay@gmail.co1', 'ajay@gmail.co1', 'd41d8cd98f00b204e9800998ecf8427e', 0.00);
INSERT INTO `users` VALUES (12, 'ajay@gmail.co13', 'ajay@gmail.co13', 'ajay@gmail.co13', 0.00);
INSERT INTO `users` VALUES (13, 'ajay@gmail.co1332', 'ajay@gmail.co1332', '23a75071720559ed67f7353ec6fad401', 0.00);
INSERT INTO `users` VALUES (14, 'fffff@qwe', 'fffff@qwe', 'efdea34d0be45c67363c321c18ffcfdc', 0.00);
INSERT INTO `users` VALUES (15, 'sdsd@13221', 'sdsd@13221', '9e9afe0760843ed9b941d75cfcea2df0', 0.00);
INSERT INTO `users` VALUES (16, '444@33', '444@33', '$2y$10$gbh4KFP/A.dglCeO0RntUeal3rSLeZ.SZeAJWpUxDYnk1tNWBEFWe', 0.00);
INSERT INTO `users` VALUES (17, 'fasdf@qwe', 'fasdf@qwe', '$2y$10$LFCVfo1QLP3boZR8ufRw3utp8yRzLzIBeUpgSMc8bimzPcXYZGWlC', 0.00);
INSERT INTO `users` VALUES (18, 'asfd@were1', 'asfd@were1', 'b0f79fe1b136278794ee5ea6ee566f5c', 0.00);
INSERT INTO `users` VALUES (19, 'aa@fiewjhfu1', 'aa@fiewjhfu1', '46dc1354db1430fc48a99cb2dde6d759', 0.00);

SET FOREIGN_KEY_CHECKS = 1;
