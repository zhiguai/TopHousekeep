-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- 主机： localhost:3306
-- 生成日期： 2023-06-27 17:18:24
-- 服务器版本： 5.6.51
-- PHP 版本： 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `a382tb0d5`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `power` int(11) NOT NULL DEFAULT '0',
  `uuid` varchar(64) DEFAULT '' COMMENT '登入凭证'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`, `power`, `uuid`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, '5989841f-5e59-f4b0-523f-0a5df30c108b'),
(2, 'admin0', 'e37a2178c21633f396315f93f63594dd80a9b737', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL COMMENT 'cid/pid/aid:1',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `content` mediumtext NOT NULL COMMENT '内容',
  `introduction` varchar(256) NOT NULL COMMENT '简介',
  `img` varchar(256) DEFAULT '' COMMENT '封面',
  `price` float DEFAULT '0' COMMENT '价格',
  `good` int(11) NOT NULL DEFAULT '0' COMMENT '推荐数',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `look` int(11) NOT NULL DEFAULT '0' COMMENT '浏览数',
  `tag` varchar(256) DEFAULT '' COMMENT '标签Json',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '上传时间',
  `ip` varchar(256) DEFAULT '' COMMENT '上传IP',
  `top` enum('true','false') DEFAULT 'false' COMMENT '置顶',
  `status` enum('true','false') DEFAULT 'false' COMMENT '展示状态',
  `ban` enum('true','false') NOT NULL DEFAULT 'false' COMMENT '封禁状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cards`
--

INSERT INTO `cards` (`id`, `uid`, `content`, `introduction`, `img`, `price`, `good`, `comments`, `look`, `tag`, `date`, `ip`, `top`, `status`, `ban`) VALUES
(1, 0, '适用于公司搬迁、学校搬迁、工厂搬迁等各类企事业单位的搬迁、搬场服务。', '适用于公司搬迁、学校搬迁等等', '/storage/image/20230624/ce2c03d6042d7e643d7c8e2f626d233e.jpg', 300, 0, 0, 25, '[\"1\"]', '2023-06-27 09:14:54', '223.88.4.60', 'false', 'true', 'false'),
(2, 0, '管道疏通   家电维修   拆装服务 ', ' 安全便捷 专业技师 修不好不收费', '/storage/image/20230624\\86f8cc9484278d5b4aae7d2ab99fe543.png', 50, 0, 0, 13, '[\"2\"]', '2023-06-26 20:19:07', '106.33.231.90', 'false', 'true', 'false'),
(3, 0, '家务员、育儿嫂、病患护理、养老护理、包月小时工', '家务员', '/storage/image/20230624\\014cc870481a38da88000bcbcd8a31a9.png', 200, 0, 0, 3, '[\"3\"]', '2023-06-27 09:11:46', '223.88.4.60', 'false', 'true', 'false'),
(4, 0, '单次·专业日常保洁4H', '单次·专业日常保洁4H', '/storage/image/20230624\\8cf992cc21210cce929b6b123108d3dc.png', 60, 0, 0, 1, '[\"4\"]', '2023-06-27 09:11:56', '223.88.4.60', 'false', 'true', 'false'),
(5, 0, '为病人提供日常陪伴和生活照护，包括帮助病人按时服药，护送病人到各科室就诊检查，为病人洗脸、手、脚、擦身、打饭、更换衣服、洗衣等，帮助病人大小便，遇特殊情况与病人家属联系等。\n', '严格筛选、场景实训、多项考核', '/storage/image/20230624\\a4d278ad286fb44deac7baaaa8da2e0f.png', 300, 0, 0, 5, '[\"5\"]', '2023-06-27 09:15:18', '223.88.4.60', 'false', 'true', 'false'),
(6, 0, '妈妈护理   宝宝护理   生活料理', '适用于婴儿日常护理、婴儿健康护理与早教、产妇日常护理、产妇身体调理等。', '/storage/image/20230624\\ce2c03d6042d7e643d7c8e2f626d233e.jpg', 300, 1, 1, 14, '[\"6\"]', '2023-06-27 09:15:12', '223.88.4.60', 'false', 'true', 'false'),
(7, 0, '为医院提供保洁服务、中央运送服务、设备运行和维护服务、绿化养护服务、会务服务、导医和电梯服务、患者配餐服务等。', '为医院提供保洁服务、中央运送服务', '/storage/image/20230624\\5f0fe9bd8afbf0eaa81413ef15a7607a.jpg', 400, 0, 0, 2, '[\"7\"]', '2023-06-27 09:12:26', '223.88.4.60', 'false', 'true', 'false'),
(8, 0, '打造方便、人性化的居家养老环境', '打造方便、人性化的居家养老环境', '/storage/image/20230624\\b3cefd2c36f0fbc557fb2206b99ce09c.png', 500, 0, 0, 2, '[\"8\"]', '2023-06-27 09:12:52', '223.88.4.60', 'false', 'true', 'false'),
(9, 0, '适用于每周一次经常打扫的居家室内。服务范围为居家室内的普通日常保洁，含8大区域保洁，40多项服务内容，详见页面说明、预约须知。', '单次·专业日常保洁4H 自营保洁师', '/storage/image/20230624\\1dd7f41a0fb36feb929d440e58aa2282.jpg', 80, 0, 0, 2, '[\"10\"]', '2023-06-27 09:13:04', '223.88.4.60', 'false', 'true', 'false'),
(10, 0, '企业、单位办公区域或仓库、厂房等开荒保洁；企业、单位办公区域或仓库、厂房等日常保洁请于【大扫除】服务工种页面预约。', '曾负责省人民大会堂的开荒保洁 ', '/storage/image/20230624\\4cdeb1715644774341f618ab2f6ec272.png', 300, 0, 0, 0, '[\"11\"]', '2023-06-27 09:13:14', '223.88.4.60', 'false', 'true', 'false'),
(11, 0, '搬家，搬单位，搬学校', '专业家庭搬家服务', '/storage/image/20230624\\b8e41f5e0c2aae061bedc703933fd6e3.png', 300, 0, 0, 0, '[\"1\"]', '2023-06-27 09:13:26', '223.88.4.60', 'false', 'true', 'false'),
(12, 0, '搬家，搬学校，搬单位', '家庭搬家 专业家庭搬家服务', '/storage/image/20230624\\ee0269c9c78ac7cb39af4af20983a2f0.png', 300, 0, 0, 0, '[\"1\"]', '2023-06-27 09:13:37', '223.88.4.60', 'false', 'true', 'false'),
(13, 0, '管道疏通，家电维修，拆装服务', '统一定价 价格优惠 安全便捷 专业技师 修不好不收费', '/storage/image/20230624\\b63d01814ac0b4e8beaf59705908af58.png', 100, 0, 0, 0, '[\"2\"]', '2023-06-24 10:24:50', '192.168.137.105', 'false', 'true', 'false'),
(14, 0, '管道疏通，家电维修，拆装服务', ' 地漏疏通 统一定价 价格优惠 安全便捷 专业技师 修不好不收费', '/storage/image/20230624\\9d1f80776ec8fad53479fc31d79cff16.png', 100, 0, 0, 1, '[\"2\"]', '2023-06-27 09:14:07', '223.88.4.60', 'false', 'true', 'false'),
(15, 0, '家务员、育儿嫂、病患护理、养老护理、包月小时工', '家务员、育儿嫂、', '/storage/image/20230624\\803ba01b011afd652650c63beeb377a4.png', 300, 0, 0, 1, '[\"3\"]', '2023-06-27 09:14:13', '223.88.4.60', 'false', 'true', 'false'),
(16, 0, '适用于每周一次经常打扫的居家室内。服务范围为居家室内的普通日常保洁，含8大区域保洁，40多项服务内容，详见页面说明、预约须知。', ' 单次·专业日常保洁4H 自营保洁师', '/storage/image/20230624\\7b3d7bcca8e96b8c5a6a2248cd6f01c1.jpg', 300, 0, 0, 0, '[\"4\"]', '2023-06-27 09:14:20', '223.88.4.60', 'false', 'true', 'false'),
(17, 0, '为病人提供日常陪伴和生活照护，包括帮助病人按时服药，护送病人到各科室就诊检查，为病人洗脸、手、脚、擦身、打饭、更换衣服、洗衣等，帮助病人大小便，遇特殊情况与病人家属联系等。', ' 病患陪护 严格筛选、场景实训、多项考核、制度管理、定期回访、实时监督', '/storage/image/20230624\\64db004a20f62e906f8dd347b8fab2ff.png', 200, 0, 0, 0, '[\"5\"]', '2023-06-27 09:14:30', '223.88.4.60', 'false', 'true', 'false'),
(18, 0, '为病人提供日常陪伴和生活照护，包括帮助病人按时服药，护送病人到各科室就诊检查，为病人洗脸、手、脚、擦身、打饭、更换衣服、洗衣等，帮助病人大小便，遇特殊情况与病人家属联系等。', '为病人提供日常陪伴和生活照护，包括帮助病人按时服药，护送病人到各科室就诊检查，为病人洗脸、手、脚、擦身、打饭、更换衣服、洗衣等，帮助病人大小便，遇特殊情况与病人家属联系等。', '/storage/image/20230624\\10a7c86e062e921748fe466419a7ebe7.jpg', 222, 0, 0, 0, '[\"6\"]', '2023-06-24 10:32:53', '192.168.137.105', 'false', 'true', 'false'),
(19, 0, '为医院提供保洁服务、中央运送服务、设备运行和维护服务、绿化养护服务、会务服务、导医和电梯服务、患者配餐服务等。', '为医院提供保洁服务、中央运送服务、设备运行和维护服务、绿化养护服务、会务服务、导医和电梯服务、患者配餐服务等。', '/storage/image/20230624\\75a3375d63fd2ed670346d9270261c2d.png', 500, 0, 0, 0, '[\"7\"]', '2023-06-24 10:34:00', '192.168.137.105', 'false', 'true', 'false'),
(20, 0, '为老人提供紧急协助、代购代取、健康监测、卫生清洁等服务，打造方便、人性化的居家养老环境。', '为老人提供紧急协助、代购代取、健康监测、卫生清洁等服务，打造方便、人性化的居家养老环境。', '/storage/image/20230624\\b32eca7cb074c957dcc41a56de03dcbb.png', 500, 0, 0, 0, '[\"8\"]', '2023-06-27 09:14:45', '223.88.4.60', 'false', 'true', 'false');

-- --------------------------------------------------------

--
-- 表的结构 `cards_comments`
--

CREATE TABLE `cards_comments` (
  `id` int(11) NOT NULL COMMENT 'pid/aid:2',
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL COMMENT 'CardsID',
  `number` varchar(256) NOT NULL,
  `content` varchar(256) NOT NULL COMMENT '内容',
  `name` varchar(256) NOT NULL COMMENT '我的名字',
  `ip` varchar(256) NOT NULL COMMENT '发布者IP',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '发布时间',
  `ban` enum('false','true') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cards_comments`
--

INSERT INTO `cards_comments` (`id`, `uid`, `cid`, `number`, `content`, `name`, `ip`, `date`, `ban`) VALUES
(1, 1, 6, 'ORD202306241908318680', '非常满意这次的服务', '测试账户', '106.33.231.90', '2023-06-25 14:07:12', 'false');

-- --------------------------------------------------------

--
-- 表的结构 `cards_tag`
--

CREATE TABLE `cards_tag` (
  `id` int(11) NOT NULL COMMENT 'pid',
  `name` varchar(8) DEFAULT '',
  `icon` varchar(256) NOT NULL DEFAULT 'add',
  `tip` varchar(64) DEFAULT '' COMMENT '提示',
  `status` enum('false','true') NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cards_tag`
--

INSERT INTO `cards_tag` (`id`, `name`, `icon`, `tip`, `status`, `date`) VALUES
(1, '搬家', 'account_balance	', '专业家庭搬家服务', 'true', '2023-06-24 09:13:57'),
(2, '管道疏通', 'account_circle	', '统一定价 价格优惠 安全便捷 专业技师 修不好不收费', 'true', '2023-06-24 09:14:03'),
(3, '家庭保姆	', 'accessibility', '适用范围：家务员、育儿嫂、病患护理、养老护理、包月小时工', 'true', '2023-06-24 09:14:06'),
(4, '清洁工', 'accessible', '回归舒适家庭', 'true', '2023-06-24 09:14:15'),
(5, '医院护工', 'add_circle_outline', '严格筛选、场景实训、多项考核、制度管理、定期回访、实时监督', 'true', '2023-06-24 09:14:19'),
(6, '月嫂', 'beenhere', '为病人提供日常陪伴和生活照护，包括帮助病人按时服药', 'true', '2023-06-24 09:14:24'),
(7, '医院后勤	', 'airline_seat_flat', '为医院提供保洁服务、中央运送服务', 'true', '2023-06-24 09:14:29'),
(8, '居家养老	', 'add_circle', '打造方便、人性化的居家养老环境', 'true', '2023-06-24 09:14:35'),
(9, '新居开荒', 'add_to_photos', '新造楼盘、未入住新房的开荒保洁', 'true', '2023-06-24 09:14:40'),
(10, '单次专业日常保洁', 'attachment', '自营保洁师', 'true', '2023-06-24 09:14:45'),
(11, '企业保洁', 'autorenew', '曾负责省人民大会堂的开荒保洁', 'true', '2023-06-24 09:14:50');

-- --------------------------------------------------------

--
-- 表的结构 `cards_tag_map`
--

CREATE TABLE `cards_tag_map` (
  `id` int(11) NOT NULL,
  `cid` int(11) DEFAULT NULL COMMENT 'CardsID',
  `tid` int(11) DEFAULT NULL COMMENT 'TagID',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cards_tag_map`
--

INSERT INTO `cards_tag_map` (`id`, `cid`, `tid`, `date`) VALUES
(40, 2, 2, '2023-06-25 13:51:46'),
(43, 3, 3, '2023-06-27 09:11:46'),
(44, 4, 4, '2023-06-27 09:11:56'),
(45, 5, 5, '2023-06-27 09:12:06'),
(46, 6, 6, '2023-06-27 09:12:16'),
(47, 7, 7, '2023-06-27 09:12:26'),
(48, 8, 8, '2023-06-27 09:12:52'),
(49, 9, 10, '2023-06-27 09:13:04'),
(50, 10, 11, '2023-06-27 09:13:14'),
(51, 11, 1, '2023-06-27 09:13:26'),
(52, 12, 1, '2023-06-27 09:13:37'),
(24, 13, 2, '2023-06-24 10:24:50'),
(53, 14, 2, '2023-06-27 09:14:07'),
(54, 15, 3, '2023-06-27 09:14:13'),
(55, 16, 4, '2023-06-27 09:14:20'),
(57, 17, 5, '2023-06-27 09:14:30'),
(35, 18, 6, '2023-06-24 10:32:53'),
(37, 19, 7, '2023-06-24 10:34:00'),
(58, 20, 8, '2023-06-27 09:14:45'),
(42, 1, 1, '2023-06-27 09:11:11');

-- --------------------------------------------------------

--
-- 表的结构 `good`
--

CREATE TABLE `good` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `aid` int(11) NOT NULL COMMENT '应用ID',
  `pid` int(11) NOT NULL COMMENT '条目ID',
  `ip` varchar(32) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `good`
--

INSERT INTO `good` (`id`, `uid`, `aid`, `pid`, `ip`, `date`) VALUES
(1, 1, 1, 6, '106.33.231.90', '2023-06-25');

-- --------------------------------------------------------

--
-- 表的结构 `img`
--

CREATE TABLE `img` (
  `id` int(11) NOT NULL,
  `aid` int(11) NOT NULL COMMENT '应用ID',
  `pid` int(11) NOT NULL COMMENT '条目ID',
  `url` varchar(256) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `img`
--

INSERT INTO `img` (`id`, `aid`, `pid`, `url`, `date`) VALUES
(125, 1, 2, '/storage/image/20230624\\7bf09a4ea67feaa902623193fdd02c3d.png', '2023-06-25 13:51:46'),
(124, 1, 2, '/storage/image/20230624\\ad247856393e0af09f1c42b3bb7eb8eb.png', '2023-06-25 13:51:46'),
(123, 1, 2, '/storage/image/20230624\\fb91d1de5d8c618d2a89050f7a011fbd.png', '2023-06-25 13:51:46'),
(122, 1, 2, '/storage/image/20230624\\86f8cc9484278d5b4aae7d2ab99fe543.png', '2023-06-25 13:51:46'),
(134, 1, 3, '/storage/image/20230624\\996d607b1cb8b33cbd90252e26b1cfd0.png', '2023-06-27 09:11:46'),
(133, 1, 3, '/storage/image/20230624\\04aa9ea96f3ab92ac8d6877751823f3a.png', '2023-06-27 09:11:46'),
(132, 1, 3, '/storage/image/20230624\\93a7d70bedc32779cc59a3187b75b834.png', '2023-06-27 09:11:46'),
(131, 1, 3, '/storage/image/20230624\\5b9ef8120755f7adda8cccba31140b70.png', '2023-06-27 09:11:46'),
(130, 1, 3, '/storage/image/20230624\\014cc870481a38da88000bcbcd8a31a9.png', '2023-06-27 09:11:46'),
(138, 1, 4, '/storage/image/20230624\\23ae5a62cb19c08c7a2cfaa619837937.png', '2023-06-27 09:11:56'),
(137, 1, 4, '/storage/image/20230624\\c319eb2edfe308cf2c8c8608678389da.png', '2023-06-27 09:11:56'),
(136, 1, 4, '/storage/image/20230624\\8cf992cc21210cce929b6b123108d3dc.png', '2023-06-27 09:11:56'),
(143, 1, 5, '/storage/image/20230624\\582060c0f7b49106274f1d9f77198114.png', '2023-06-27 09:12:06'),
(142, 1, 5, '/storage/image/20230624\\ec89b9486399c84d6dd52e910fc5413d.png', '2023-06-27 09:12:06'),
(141, 1, 5, '/storage/image/20230624\\58477de16d471fa578462527e3deff32.png', '2023-06-27 09:12:06'),
(140, 1, 5, '/storage/image/20230624\\a4d278ad286fb44deac7baaaa8da2e0f.png', '2023-06-27 09:12:06'),
(147, 1, 6, '/storage/image/20230624\\6504fb88efbce97c4d2cdd861ac0236b.jpg', '2023-06-27 09:12:16'),
(146, 1, 6, '/storage/image/20230624\\3fa3afd9f3f2aeb25143d2beaf40fb16.png', '2023-06-27 09:12:16'),
(145, 1, 6, '/storage/image/20230624\\ce2c03d6042d7e643d7c8e2f626d233e.jpg', '2023-06-27 09:12:16'),
(151, 1, 7, '/storage/image/20230624\\cc64979c4c4bfb43dc4617a7feb68530.png', '2023-06-27 09:12:26'),
(150, 1, 7, '/storage/image/20230624\\8ead6f04827135ffb433b37c18e051e7.png', '2023-06-27 09:12:26'),
(149, 1, 7, '/storage/image/20230624\\5f0fe9bd8afbf0eaa81413ef15a7607a.jpg', '2023-06-27 09:12:26'),
(155, 1, 8, '/storage/image/20230624\\c38f15edd4f27a47695078bfc6cd4905.png', '2023-06-27 09:12:52'),
(154, 1, 8, '/storage/image/20230624\\211901159fee153a32d5db5e781aa0a6.png', '2023-06-27 09:12:52'),
(153, 1, 8, '/storage/image/20230624\\b3cefd2c36f0fbc557fb2206b99ce09c.png', '2023-06-27 09:12:52'),
(159, 1, 9, '/storage/image/20230624\\6be598e09f3e359a4f4b6972bc06855d.png', '2023-06-27 09:13:04'),
(158, 1, 9, '/storage/image/20230624\\d16637fb723bae6cff588885d9e165c5.png', '2023-06-27 09:13:04'),
(157, 1, 9, '/storage/image/20230624\\1dd7f41a0fb36feb929d440e58aa2282.jpg', '2023-06-27 09:13:04'),
(163, 1, 10, '/storage/image/20230624\\b41b21944d671c4734419f722487766d.jpg', '2023-06-27 09:13:14'),
(162, 1, 10, '/storage/image/20230624\\59c496992f71afff73962bfe7b642c3a.png', '2023-06-27 09:13:14'),
(161, 1, 10, '/storage/image/20230624\\4cdeb1715644774341f618ab2f6ec272.png', '2023-06-27 09:13:14'),
(167, 1, 11, '/storage/image/20230624\\b19ad748ae695a98532bab70a32c739a.png', '2023-06-27 09:13:26'),
(166, 1, 11, '/storage/image/20230624\\88767ca4719e675c7b7d2f86dd407b0e.png', '2023-06-27 09:13:26'),
(165, 1, 11, '/storage/image/20230624\\b8e41f5e0c2aae061bedc703933fd6e3.png', '2023-06-27 09:13:26'),
(171, 1, 12, '/storage/image/20230624\\9e14212287c6340e8785758a3710aa34.png', '2023-06-27 09:13:37'),
(170, 1, 12, '/storage/image/20230624\\c597f30c83af6d3f41065d8e8693eb3c.png', '2023-06-27 09:13:37'),
(169, 1, 12, '/storage/image/20230624\\ee0269c9c78ac7cb39af4af20983a2f0.png', '2023-06-27 09:13:37'),
(80, 1, 13, '/storage/image/20230624\\b9c0b117198beeada0e86e63da23646c.png', '2023-06-24 10:24:50'),
(79, 1, 13, '/storage/image/20230624\\96b46395d94b01f2fcb40c4afec9c2d2.png', '2023-06-24 10:24:50'),
(78, 1, 13, '/storage/image/20230624\\b63d01814ac0b4e8beaf59705908af58.png', '2023-06-24 10:24:50'),
(175, 1, 14, '/storage/image/20230624\\4b37fd3d57ff380687ce55c46e1f921b.png', '2023-06-27 09:14:07'),
(174, 1, 14, '/storage/image/20230624\\cd86acd50b7dcf3583fac46ededffd45.png', '2023-06-27 09:14:07'),
(173, 1, 14, '/storage/image/20230624\\9d1f80776ec8fad53479fc31d79cff16.png', '2023-06-27 09:14:07'),
(179, 1, 15, '/storage/image/20230624\\07e6d534b5f557fcbb9503d96d9033fd.png', '2023-06-27 09:14:13'),
(178, 1, 15, '/storage/image/20230624\\9369cd925dc51b28956af4b953a1e602.png', '2023-06-27 09:14:13'),
(177, 1, 15, '/storage/image/20230624\\803ba01b011afd652650c63beeb377a4.png', '2023-06-27 09:14:13'),
(183, 1, 16, '/storage/image/20230624\\9d431715202829203bad66e155165774.jpg', '2023-06-27 09:14:20'),
(182, 1, 16, '/storage/image/20230624\\e1ceb9332c0f57ab7e015bb874d98bd8.jpg', '2023-06-27 09:14:20'),
(181, 1, 16, '/storage/image/20230624\\7b3d7bcca8e96b8c5a6a2248cd6f01c1.jpg', '2023-06-27 09:14:20'),
(192, 1, 17, '/storage/image/20230627/fb19650fe26ac29f059810542357f0e6.png', '2023-06-27 09:14:30'),
(191, 1, 17, '/storage/image/20230624\\b546af2ea23c37ac38fb2f838eb98bba.png', '2023-06-27 09:14:30'),
(190, 1, 17, '/storage/image/20230624\\9c05a388382d8971b820d4193bcca115.png', '2023-06-27 09:14:30'),
(111, 1, 18, '/storage/image/20230624\\72acd6bacaaed0b151f4bea4643ec20f.jpg', '2023-06-24 10:32:53'),
(110, 1, 18, '/storage/image/20230624\\10a7c86e062e921748fe466419a7ebe7.jpg', '2023-06-24 10:32:53'),
(117, 1, 19, '/storage/image/20230624\\6c1f67d3371edfe022500726f3a8a3a7.png', '2023-06-24 10:34:00'),
(116, 1, 19, '/storage/image/20230624\\2601f164be50e9172b072bd5cfe17e63.jpg', '2023-06-24 10:34:00'),
(115, 1, 19, '/storage/image/20230624\\75a3375d63fd2ed670346d9270261c2d.png', '2023-06-24 10:34:00'),
(194, 1, 20, '/storage/image/20230624\\c79ac0345f80dd5e33871317a69b87c3.jpg', '2023-06-27 09:14:45'),
(193, 1, 20, '/storage/image/20230624\\b32eca7cb074c957dcc41a56de03dcbb.png', '2023-06-27 09:14:45'),
(126, 1, 2, 'https://img1.imgtp.com/2023/06/24/PCKUt960.jpg', '2023-06-25 13:51:46'),
(128, 1, 1, '/storage/image/20230624/ce2c03d6042d7e643d7c8e2f626d233e.jpg', '2023-06-27 09:11:11'),
(129, 1, 1, '/storage/image/20230627/eb0ca8e6b044ce04a221c22b555d5f4f.png', '2023-06-27 09:11:11'),
(135, 1, 3, '/storage/image/20230627/0f77fa779e8bfe3701fa5f57b4acebf9.png', '2023-06-27 09:11:46'),
(139, 1, 4, '/storage/image/20230627/daa97e07e1d8641dd9bbf240aa8efe71.png', '2023-06-27 09:11:56'),
(144, 1, 5, '/storage/image/20230627/48bd0bdd7aa84c4a2d63523e01439e0e.png', '2023-06-27 09:12:06'),
(148, 1, 6, '/storage/image/20230627/fec3a20da8cf392307a3c8d18f7b915a.png', '2023-06-27 09:12:16'),
(152, 1, 7, '/storage/image/20230627/f12fa4ac6244301a37f86dd5df6d07bb.png', '2023-06-27 09:12:26'),
(156, 1, 8, 'http://wx.xcx.tophousekeep.chizg.cn/storage/image/20230627/79e6e94ee4214005b9d534d73d967114.png', '2023-06-27 09:12:52'),
(160, 1, 9, '/storage/image/20230627/3bc3c470f2299caccfa6fb85ff9e96aa.png', '2023-06-27 09:13:04'),
(164, 1, 10, '/storage/image/20230627/cf53411910402ea6d581bc0495d93f43.png', '2023-06-27 09:13:14'),
(168, 1, 11, '/storage/image/20230627/d6c5f6c40fc0f7cb7ebf66b4e2677596.png', '2023-06-27 09:13:26'),
(172, 1, 12, '/storage/image/20230627/1b654f5f483429140b9f3edd5ca10952.png', '2023-06-27 09:13:37'),
(176, 1, 14, '/storage/image/20230627/fb19650fe26ac29f059810542357f0e6.png', '2023-06-27 09:14:07'),
(180, 1, 15, '/storage/image/20230627/fb19650fe26ac29f059810542357f0e6.png', '2023-06-27 09:14:13'),
(184, 1, 16, '/storage/image/20230627/fb19650fe26ac29f059810542357f0e6.png', '2023-06-27 09:14:20'),
(189, 1, 17, '/storage/image/20230624\\64db004a20f62e906f8dd347b8fab2ff.png', '2023-06-27 09:14:30'),
(195, 1, 20, '/storage/image/20230627/fb19650fe26ac29f059810542357f0e6.png', '2023-06-27 09:14:45');

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `cid` varchar(256) NOT NULL COMMENT 'CardsID',
  `uid` int(11) NOT NULL,
  `number` varchar(256) NOT NULL COMMENT '订单号',
  `price` float NOT NULL,
  `address` varchar(256) NOT NULL,
  `note` varchar(256) NOT NULL,
  `service_date` datetime NOT NULL,
  `status` enum('0','1','2','3','4') NOT NULL COMMENT '0订单未开始/1未开始/2进行中/3结束/4订单结束',
  `creat_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建日期',
  `ban` enum('false','true') NOT NULL,
  `ip` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`id`, `cid`, `uid`, `number`, `price`, `address`, `note`, `service_date`, `status`, `creat_date`, `ban`, `ip`) VALUES
(1, '1', 1, 'ORD202306241854151280', 300, '郑州丰收路', '抓紧时间', '2023-06-24 20:45:00', '2', '2023-06-24 10:54:15', 'false', '192.168.137.105'),
(2, '2', 1, 'ORD202306241859277677', 50, '郑州商学院', '建设大道', '2023-06-30 18:59:00', '0', '2023-06-24 10:59:27', 'false', '192.168.137.105'),
(3, '3', 1, 'ORD202306241900079118', 200, '中山路116号', '加快速度', '2023-06-21 18:59:00', '4', '2023-06-24 11:00:07', 'false', '192.168.137.105'),
(4, '5', 1, 'ORD202306241901033251', 300, '大道路185号', '要求经验丰富', '2023-06-30 19:00:00', '0', '2023-06-24 11:01:03', 'false', '192.168.137.105'),
(5, '5', 1, 'ORD202306241901552757', 300, '大同路\n', '经验丰富，体贴温柔', '2023-06-13 19:01:00', '0', '2023-06-24 11:01:55', 'false', '192.168.137.105'),
(6, '6', 1, 'ORD202306241902303077', 300, '东健路', '要求长期', '2023-06-27 19:02:00', '1', '2023-06-24 11:02:30', 'false', '192.168.137.105'),
(7, '7', 1, 'ORD202306241906189582', 400, '紫荆路', '大道路', '2023-06-10 19:02:00', '2', '2023-06-24 11:06:18', 'false', '192.168.137.105'),
(8, '1', 1, 'ORD202306241907437260', 300, '小云路', '经验丰富', '2023-06-09 19:07:00', '3', '2023-06-24 11:07:43', 'false', '192.168.137.105'),
(9, '6', 1, 'ORD202306241908318680', 300, '黄金路', '长期', '2023-05-20 19:08:00', '4', '2023-06-24 11:08:31', 'false', '192.168.137.105'),
(10, '1', 2, 'ORD202306262233037108', 300, '河南省 /郑州市/巩义市', '紧急使用！', '2023-06-14 22:32:00', '0', '2023-06-26 14:33:03', 'false', '39.144.177.17');

-- --------------------------------------------------------

--
-- 表的结构 `system`
--

CREATE TABLE `system` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT '',
  `value` varchar(2555) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `system`
--

INSERT INTO `system` (`id`, `name`, `value`) VALUES
(1, 'siteUrl', 'wmjz.com'),
(2, 'siteName', '完美家政平台'),
(3, 'siteICPId', '备案信息'),
(4, 'siteKeywords', 'TopHouseKeep,完美家政,百分家政'),
(5, 'siteDes', '本站由完美家政强力驱动'),
(6, 'smtpUser', ''),
(7, 'smtpHost', ''),
(8, 'smtpPort', ''),
(9, 'smtpPass', ''),
(10, 'siteFooter', ''),
(11, 'LCEAPI', ''),
(12, 'siteCopyright', '©2018-2023 LoveCards. All Rights Reserved.All Rights Reserved－保留所有权利'),
(13, 'siteTitle', '点一点家政就到家'),
(14, 'smtpSecure', ''),
(15, 'smtpName', '');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL COMMENT '邮箱',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `avatar` varchar(256) NOT NULL DEFAULT '' COMMENT '头像',
  `name` varchar(11) NOT NULL COMMENT '用户名',
  `password` varchar(256) NOT NULL COMMENT '密码',
  `ban` enum('true','false') NOT NULL COMMENT '用户状态',
  `login_date` datetime NOT NULL COMMENT '登入日期',
  `ip` varchar(32) NOT NULL,
  `registration_date` datetime NOT NULL COMMENT '注册日期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `email`, `phone`, `avatar`, `name`, `password`, `ban`, `login_date`, `ip`, `registration_date`) VALUES
(1, 'user@tophousekeep.cn', '15689523625', '', '测试账户', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'false', '2023-06-27 17:06:21', '192.168.137.105', '2023-06-24 18:43:14'),
(2, '123456789@qq.com', '12345678911', '', 'liu', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'false', '2023-06-26 22:26:56', '39.144.177.17', '2023-06-26 22:26:56');

--
-- 转储表的索引
--

--
-- 表的索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cards_comments`
--
ALTER TABLE `cards_comments`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cards_tag`
--
ALTER TABLE `cards_tag`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cards_tag_map`
--
ALTER TABLE `cards_tag_map`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `good`
--
ALTER TABLE `good`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `img`
--
ALTER TABLE `img`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`);

--
-- 表的索引 `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'cid/pid/aid:1', AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `cards_comments`
--
ALTER TABLE `cards_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'pid/aid:2', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `cards_tag`
--
ALTER TABLE `cards_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'pid', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `cards_tag_map`
--
ALTER TABLE `cards_tag_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- 使用表AUTO_INCREMENT `good`
--
ALTER TABLE `good`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `img`
--
ALTER TABLE `img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `system`
--
ALTER TABLE `system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
