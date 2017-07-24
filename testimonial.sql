/*
Navicat MySQL Data Transfer

Source Server         : LOCAL
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : latwp2

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2017-07-07 18:37:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wp_testimonial`
-- ----------------------------
DROP TABLE IF EXISTS `wp_testimonial`;
CREATE TABLE `wp_testimonial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `testimonial` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of wp_testimonial
-- ----------------------------
INSERT INTO `wp_testimonial` VALUES ('1', 'Edgar Alan Poe', 'edgar224@gmail.com', '+21619921000', 'Another really cool, unique thing they do? Each client story module links to the client\\\'s website, Facebook page, and app in both the Android and Apple app stores. Now that\\\'s loving your clients back.');
INSERT INTO `wp_testimonial` VALUES ('2', 'John Doe', 'doniexxx@yahoo.com', '+1461224679988', 'When potential customers are researching you online, they\\\'re getting to know you by way of the content of your website. Understandably, many of them might be skeptical or hesitant to trust you right away.');
INSERT INTO `wp_testimonial` VALUES ('4', 'Martha Stewart', 'mareta2315@gmail.com', '+237718992001', 'Social media is a great source of social proof, and many customers turn to places like Twitter and Facebook to informally review businesses they buy from. Be sure to monitor your social media presence regularly to find tweets, Facebook posts, Instagram posts, and so on that positively reflect your brand, and see where you can embed them on your website. ');
