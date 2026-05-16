-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 16, 2026 lúc 05:44 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `evashop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ai_recommendations`
--

CREATE TABLE `ai_recommendations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `recommended_product_id` int(11) NOT NULL,
  `score` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ai_recommendations`
--

INSERT INTO `ai_recommendations` (`id`, `product_id`, `recommended_product_id`, `score`) VALUES
(1, 124, 138, 0.9652),
(2, 138, 124, 0.9652),
(3, 75, 165, 0.977675),
(4, 165, 75, 0.977675),
(5, 109, 153, 0.978078),
(6, 153, 109, 0.978078);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `hinh_anh` varchar(255) NOT NULL,
  `tieu_de` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `noi_dung` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `link_lien_ket` varchar(255) DEFAULT NULL,
  `thu_tu` int(11) DEFAULT 0,
  `trang_thai` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banner`
--

INSERT INTO `banner` (`id`, `hinh_anh`, `tieu_de`, `noi_dung`, `link_lien_ket`, `thu_tu`, `trang_thai`) VALUES
(1, 'banner1.png', 'KHAI TRƯƠNG EVA SHOP', 'Giảm giá 20% cho tất cả đơn hàng đầu tiên', 'san_pham.php', 1, 1),
(2, 'banner2.png', 'VỢT YONEX 2025', 'Bộ sưu tập mới nhất vừa cập bến', 'san_pham.php?thuonghieu=1', 2, 1),
(3, 'banner3.png', 'GIÀY CẦU LÔNG CHÍNH HÃNG', 'Bảo vệ đôi chân - Nâng tầm chiến thắng', 'san_pham.php?danhmuc=2', 3, 1),
(4, 'banner4.png', 'GIÀY CẦU LÔNG CHÍNH HÃNG', 'Bảo vệ đôi chân - Nâng tầm chiến thắng', 'san_pham.php?danhmuc=2', 4, 0),
(5, 'banner5.png', 'GIÀY CẦU LÔNG CHÍNH HÃNG', 'Bảo vệ đôi chân - Nâng tầm chiến thắng', 'san_pham.php?danhmuc=2', 5, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `id` int(11) NOT NULL,
  `donhang_id` int(11) DEFAULT NULL,
  `sanpham_id` int(11) DEFAULT NULL,
  `so_luong` int(11) DEFAULT NULL,
  `don_gia` decimal(18,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`id`, `donhang_id`, `sanpham_id`, `so_luong`, `don_gia`) VALUES
(1, 2, 75, 1, 3033000),
(2, 3, 52, 1, 3100000),
(3, 4, 168, 1, 175000),
(4, 5, 168, 1, 175000),
(5, 6, 168, 1, 175000),
(6, 7, 168, 1, 175000),
(7, 8, 168, 1, 175000),
(8, 9, 168, 1, 175000),
(9, 10, 168, 1, 175000),
(10, 10, 109, 1, 1250000),
(11, 11, 168, 1, 175000),
(12, 11, 109, 1, 1250000),
(13, 12, 168, 1, 175000),
(15, 14, 110, 1, 319000),
(16, 15, 168, 1, 175000),
(17, 16, 166, 1, 160000),
(18, 17, 103, 1, 1200000),
(19, 18, 168, 1, 175000),
(20, 19, 168, 1, 175000),
(21, 20, 168, 1, 175000),
(22, 21, 78, 1, 880000),
(23, 22, 78, 1, 880000),
(24, 23, 78, 1, 880000),
(25, 24, 78, 1, 880000),
(26, 25, 148, 1, 1490000),
(27, 26, 167, 1, 160000),
(28, 27, 168, 1, 175000),
(29, 28, 168, 1, 175000),
(30, 29, 167, 2, 160000),
(31, 30, 166, 1, 160000),
(32, 31, 165, 1, 125000),
(33, 32, 165, 2, 125000),
(34, 32, 166, 1, 160000),
(35, 33, 126, 1, 139000),
(36, 34, 168, 1, 175000),
(37, 35, 168, 1, 175000),
(38, 36, 165, 1, 125000),
(39, 37, 161, 1, 150000),
(40, 38, 166, 1, 160000),
(41, 39, 167, 4, 160000),
(42, 40, 167, 1, 160000),
(43, 41, 154, 1, 120000),
(44, 42, 165, 1, 125000),
(45, 43, 80, 1, 880000),
(46, 44, 168, 1, 175000),
(47, 45, 168, 1, 175000),
(48, 46, 168, 1, 175000),
(49, 47, 167, 1, 160000),
(50, 48, 167, 2, 160000),
(51, 49, 165, 1, 125000),
(52, 50, 167, 1, 160000),
(53, 51, 165, 1, 125000),
(54, 52, 167, 1, 160000),
(55, 53, 137, 1, 130000),
(56, 54, 168, 1, 175000),
(57, 55, 124, 1, 250000),
(58, 55, 138, 1, 130000),
(59, 56, 124, 1, 250000),
(60, 56, 138, 1, 130000),
(61, 57, 138, 1, 130000),
(62, 58, 124, 1, 250000),
(63, 58, 138, 1, 130000),
(64, 59, 124, 1, 250000),
(65, 59, 138, 1, 130000),
(66, 60, 124, 1, 250000),
(67, 60, 138, 1, 130000),
(68, 61, 124, 1, 250000),
(69, 61, 138, 1, 130000),
(70, 62, 124, 1, 250000),
(71, 62, 138, 1, 130000),
(72, 63, 124, 1, 250000),
(73, 63, 138, 1, 130000),
(74, 64, 124, 1, 250000),
(75, 64, 138, 1, 130000),
(76, 65, 124, 1, 250000),
(77, 65, 138, 1, 130000),
(78, 66, 124, 1, 250000),
(79, 66, 138, 1, 130000),
(80, 67, 75, 1, 3033000),
(81, 67, 165, 1, 125000),
(82, 68, 75, 1, 3033000),
(83, 68, 165, 1, 125000),
(84, 69, 75, 1, 3033000),
(85, 69, 165, 1, 125000),
(86, 70, 75, 1, 3033000),
(87, 70, 165, 1, 125000),
(88, 71, 75, 1, 3033000),
(89, 71, 165, 1, 125000),
(90, 72, 109, 1, 1250000),
(91, 72, 153, 1, 1280000),
(92, 73, 109, 1, 1250000),
(93, 73, 153, 1, 1280000),
(94, 74, 109, 1, 1250000),
(95, 74, 153, 1, 1280000),
(96, 75, 109, 1, 1250000),
(97, 75, 153, 1, 1280000),
(98, 76, 109, 1, 1250000),
(99, 76, 153, 1, 1280000),
(100, 77, 165, 1, 125000),
(101, 78, 168, 1, 5000),
(102, 79, 168, 1, 5000),
(103, 80, 168, 1, 5000),
(104, 81, 168, 1, 5000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sanpham_id` int(11) NOT NULL,
  `so_sao` tinyint(1) NOT NULL,
  `noi_dung` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `id` int(11) NOT NULL,
  `ten_danhmuc` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`id`, `ten_danhmuc`) VALUES
(1, 'Vợt Cầu Lông'),
(2, 'Giày Cầu Lông'),
(3, 'Áo Cầu Lông'),
(4, 'Quần Cầu Lông'),
(5, 'Balo - Túi'),
(6, 'Cước Cầu Lông');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `trang_thai` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Mới',
  `hoten_nguoinhan` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `sdt_nguoinhan` varchar(20) DEFAULT NULL,
  `diachi_nguoinhan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `tong_tien` decimal(18,0) DEFAULT NULL,
  `phuong_thuc_tt` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'COD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`id`, `username`, `user_id`, `ngay_dat`, `trang_thai`, `hoten_nguoinhan`, `sdt_nguoinhan`, `diachi_nguoinhan`, `tong_tien`, `phuong_thuc_tt`) VALUES
(2, NULL, 0, '2025-12-06 13:43:20', 'Mới', 'Huỳnh Quốc Vinh', 'q', 'q', 3033000, 'COD'),
(3, NULL, 0, '2025-12-06 14:20:03', 'Mới', 'hqa555', 'q', 'q', 3100000, 'COD'),
(4, NULL, 0, '2025-12-08 14:43:32', 'Mới', 'Huỳnh Quốc Vinh', '033', 'q', 175000, 'COD'),
(5, NULL, 0, '2025-12-10 10:31:37', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '033', '123', 175000, 'MOMO'),
(6, NULL, 0, '2025-12-10 10:33:16', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '033', '123', 175000, 'MOMO'),
(7, NULL, 0, '2025-12-10 10:34:55', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '033', '123', 175000, 'MOMO'),
(8, NULL, 0, '2025-12-10 10:36:27', 'Chờ thanh toán', 'hqa555', 'q', 'qưer', 175000, 'MOMO'),
(9, NULL, 0, '2025-12-10 10:37:39', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '033', '123', 175000, 'MOMO'),
(10, NULL, 0, '2025-12-10 10:44:00', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '033', 'qưe', 1425000, 'MOMO'),
(11, NULL, 0, '2025-12-10 10:54:19', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', 'q', 'q', 1425000, 'MOMO'),
(12, NULL, 0, '2025-12-10 11:34:03', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', 'q', 'q', 175000, 'MOMO'),
(14, NULL, 0, '2025-12-11 20:23:37', 'Mới', 'hahaa', '022', 'q', 319000, 'COD'),
(15, NULL, 0, '2025-12-13 08:39:45', 'Mới', 'Quản Trị Viên', '022', 'q', 175000, 'COD'),
(16, NULL, 0, '2025-12-16 15:20:31', 'Đang giao', 'Huỳnh Quốc Vinh', 'q', 'q', 160000, 'COD'),
(17, 'admin', 0, '2025-12-16 15:35:57', 'Hoàn thành', 'hqa555', '022', 'q', 1200000, 'COD'),
(18, 'hqa', 0, '2025-12-17 08:25:11', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 175000, 'MOMO'),
(19, 'hqa', 0, '2025-12-17 08:27:11', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 175000, 'MOMO'),
(20, 'hqa', 0, '2025-12-17 08:28:49', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 175000, 'COD'),
(21, 'hqa', 0, '2025-12-17 08:31:57', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 880000, 'MOMO'),
(22, 'hqa', 0, '2025-12-17 08:34:36', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 880000, 'MOMO'),
(23, 'hqa', 0, '2025-12-17 08:34:44', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 880000, 'MOMO'),
(24, 'hqa', 0, '2025-12-17 08:35:07', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 880000, 'MOMO'),
(25, 'vinh2613', 0, '2025-12-19 21:38:01', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 1490000, 'BANK'),
(26, 'vinh2613', 0, '2025-12-20 11:47:35', 'Đã thanh toán', 'q', '0344138743', 'bb', 160000, 'BANK'),
(27, 'hqa', 0, '2025-12-20 12:30:06', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 175000, 'COD'),
(28, 'hqa', 0, '2025-12-20 12:44:24', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 174900, 'COD'),
(29, 'hqa', 0, '2025-12-21 12:38:49', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 320000, 'COD'),
(30, 'hqa', 0, '2025-12-21 12:42:25', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 160000, 'COD'),
(31, 'hqa', 0, '2025-12-21 12:47:08', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 125000, 'COD'),
(32, 'hqa', 0, '2025-12-21 13:02:35', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 410000, 'COD'),
(33, 'khangvi', 0, '2025-12-21 15:00:00', 'Chờ thanh toán', 'Trần Khang Vĩ', '022', 'hq', 139000, 'COD'),
(34, 'vinh2613', 0, '2025-12-23 21:35:32', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 175000, 'MOMO'),
(35, 'vinh2613', 0, '2025-12-23 21:48:01', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 175000, 'MOMO'),
(36, 'vinh2613', 0, '2025-12-23 21:49:22', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', '123', 125000, 'COD'),
(37, 'vinh2613', 0, '2025-12-23 21:55:23', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', '12', 150000, 'COD'),
(38, 'vinh2613', 0, '2025-12-23 22:05:23', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', '123', 160000, 'COD'),
(39, 'vinh2613', 0, '2025-12-23 22:06:29', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '12', 640000, 'MOMO'),
(40, 'vinh2613', 0, '2025-12-23 22:07:25', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 160000, 'MOMO'),
(41, 'vinh2613', 0, '2025-12-23 22:12:10', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 120000, 'MOMO'),
(42, 'vinh2613', 0, '2025-12-23 23:16:10', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '12', 125000, 'ZALOPAY'),
(43, 'vinh2613', 0, '2025-12-23 23:22:13', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 880000, 'ZALOPAY'),
(44, 'vinh2613', 0, '2025-12-27 22:00:26', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 175000, 'MOMO'),
(45, 'vinh2613', 0, '2025-12-27 22:09:04', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 175000, 'MOMO'),
(46, 'vinh2613', 0, '2025-12-27 22:11:07', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 175000, 'MOMO'),
(47, 'vinh2613', 0, '2025-12-30 10:28:38', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 108500, 'MOMO'),
(48, 'vinh2613', 0, '2025-12-30 12:42:00', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 320000, 'MOMO'),
(49, 'vinh2613', 0, '2025-12-30 12:42:18', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 125000, 'MOMO'),
(50, 'vinh2613', 0, '2025-12-30 12:51:11', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', '123', 160000, 'COD'),
(51, 'vinh2613', 0, '2025-12-30 12:51:36', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 125000, 'MOMO'),
(52, 'vinh2613', 0, '2025-12-30 13:25:51', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 160000, 'MOMO'),
(53, 'hqa', 0, '2025-12-30 13:34:08', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', 'h246/85 chánh nghĩa bình dương', 30000, 'MOMO'),
(54, 'vinh2613', 0, '2026-03-22 12:43:56', 'Chờ thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 175000, 'MOMO'),
(55, 'admin', 0, '2026-03-29 14:43:11', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '212', 380000, 'COD'),
(56, 'admin', 0, '2026-03-29 14:51:08', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(57, 'admin', 0, '2026-03-29 14:51:28', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '23', 130000, 'COD'),
(58, 'admin', 0, '2026-03-29 14:51:44', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '2', 380000, 'COD'),
(59, 'admin', 0, '2026-03-29 14:52:06', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '54', 380000, 'COD'),
(60, 'vinh2613', 0, '2026-03-29 14:59:49', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(61, 'vinh2613', 0, '2026-03-29 15:00:33', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(62, 'vinh2613', 0, '2026-03-29 15:00:55', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(63, 'vinh2613', 0, '2026-03-29 15:01:13', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(64, 'vinh2613', 0, '2026-03-29 15:01:32', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(65, 'vinh2613', 0, '2026-03-29 15:01:53', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(66, 'vinh2613', 0, '2026-03-29 15:02:11', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 380000, 'COD'),
(67, 'vinh2613', 0, '2026-03-29 15:11:49', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 3158000, 'COD'),
(68, 'vinh2613', 0, '2026-03-29 15:12:07', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 3158000, 'COD'),
(69, 'vinh2613', 0, '2026-03-29 15:12:25', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 3158000, 'COD'),
(70, 'vinh2613', 0, '2026-03-29 15:12:41', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 3158000, 'COD'),
(71, 'vinh2613', 0, '2026-03-29 15:12:57', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 3158000, 'COD'),
(72, 'vinh2613', 0, '2026-04-01 07:37:26', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 2530000, 'COD'),
(73, 'vinh2613', 0, '2026-04-01 07:37:52', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 2530000, 'COD'),
(74, 'vinh2613', 0, '2026-04-01 07:38:13', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 2530000, 'COD'),
(75, 'vinh2613', 0, '2026-04-01 07:38:32', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 2530000, 'COD'),
(76, 'vinh2613', 0, '2026-04-01 07:38:49', 'Hoàn thành', 'Huỳnh Quốc Vinh', '0344138743', '123', 2530000, 'COD'),
(77, 'vinh2613', 0, '2026-05-16 20:25:18', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', '123', 125000, 'BANK'),
(78, 'vinh2613', 0, '2026-05-16 21:21:42', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', '123', 5000, 'BANK'),
(79, 'vinh2613', 0, '2026-05-16 21:22:27', 'Mới', 'Huỳnh Quốc Vinh', '0344138743', '123', 5000, 'BANK'),
(80, 'vinh2613', 0, '2026-05-16 21:28:08', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 5000, 'BANK'),
(81, 'vinh2613', 0, '2026-05-16 21:58:22', 'Đã thanh toán', 'Huỳnh Quốc Vinh', '0344138743', '123', 5000, 'MOMO');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kho_voucher`
--

CREATE TABLE `kho_voucher` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ten_voucher` varchar(100) NOT NULL,
  `ma_code` varchar(50) NOT NULL,
  `gia_tri` int(11) NOT NULL COMMENT 'Số tiền giảm',
  `trang_thai` int(1) DEFAULT 0 COMMENT '0: Chưa dùng, 1: Đã dùng',
  `ngay_trung` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `kho_voucher`
--

INSERT INTO `kho_voucher` (`id`, `username`, `ten_voucher`, `ma_code`, `gia_tri`, `trang_thai`, `ngay_trung`) VALUES
(1, 'hqa', 'Voucher 20k', 'VC20K4792', 20000, 0, '2025-12-20 13:58:45'),
(2, 'hqa', 'Voucher 20k', 'VC20K5544', 20000, 0, '2025-12-20 14:06:58'),
(3, 'hqa', 'Voucher 20k', 'VC20K6924', 20000, 0, '2025-12-20 14:08:15'),
(4, 'hqa', 'Voucher 50k', 'VC50K6585', 50000, 0, '2025-12-20 14:12:35'),
(5, 'khangvi', 'Voucher 50k', 'VC50K6038', 50000, 0, '2025-12-21 15:01:41'),
(6, 'khangvi', 'Voucher 10k', 'VC10K7553', 10000, 0, '2025-12-21 15:02:20'),
(7, 'vinh2613', 'Voucher 10k', 'VC10K3906', 10000, 0, '2025-12-25 15:13:50'),
(8, 'vinh2613', 'Voucher 50k', 'VC50K6291', 50000, 1, '2025-12-25 16:05:43'),
(9, 'tenvaho', 'Voucher 10k', 'VC10K1114', 10000, 0, '2025-12-29 14:06:47'),
(10, 'hqa', 'Voucher 100k', 'VC100K4019', 100000, 1, '2025-12-30 13:33:31'),
(11, 'hqa', 'Voucher 10k', 'VC10K5729', 10000, 0, '2025-12-30 13:35:48'),
(12, 'hqa', 'Voucher 20k', 'VC20K9177', 20000, 0, '2025-12-30 13:36:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_diem_danh`
--

CREATE TABLE `lich_su_diem_danh` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `ngay_diem_danh` date DEFAULT NULL,
  `chuoi_ngay` int(11) DEFAULT 1,
  `diem_nhan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_diem_danh`
--

INSERT INTO `lich_su_diem_danh` (`id`, `username`, `ngay_diem_danh`, `chuoi_ngay`, `diem_nhan`) VALUES
(2, 'vinh2613', '2025-12-20', 1, 100),
(3, 'hqa', '2025-12-20', 1, 100),
(4, 'hqa', '2025-12-21', 2, 200),
(5, 'khangvi', '2025-12-21', 1, 100),
(6, 'vinh2613', '2025-12-25', 1, 100),
(7, 'tenvaho', '2025-12-29', 1, 100),
(8, 'vinh2613', '2025-12-30', 1, 100),
(9, 'hqa', '2025-12-30', 1, 100);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lienhe`
--

CREATE TABLE `lienhe` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `noi_dung` text NOT NULL,
  `trang_thai` tinyint(4) DEFAULT 0,
  `ngay_gui` datetime DEFAULT current_timestamp(),
  `phan_hoi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lienhe`
--

INSERT INTO `lienhe` (`id`, `ho_ten`, `email`, `sdt`, `noi_dung`, `trang_thai`, `ngay_gui`, `phan_hoi`) VALUES
(1, 'Huỳnh Quốc Vinh', 'vinh26132005@gmail.com', '', 'hello', 1, '2025-12-29 14:39:18', 'chào');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `id` int(11) NOT NULL,
  `ma_sp` varchar(50) NOT NULL,
  `ten_sanpham` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gia` decimal(18,0) NOT NULL,
  `thong_so` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `thuonghieu_id` int(11) DEFAULT NULL,
  `danhmuc_id` int(11) DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `mo_ta` longtext DEFAULT NULL,
  `trang_thai` tinyint(4) DEFAULT 1,
  `so_luong` int(11) DEFAULT 100,
  `luot_xem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`id`, `ma_sp`, `ten_sanpham`, `gia`, `thong_so`, `thuonghieu_id`, `danhmuc_id`, `hinh_anh`, `mo_ta`, `trang_thai`, `so_luong`, `luot_xem`) VALUES
(1, 'VNB026679', 'Vợt cầu lông Yonex Astrox 100 Game VA', 2849000, '4U5, 4U6', 1, 1, 'VNB026679.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 100 Game VA\n- Lấy cảm hứng từ phiên bản cao cấp Astrox 100ZZ VA, cây vợt này được tinh chỉnh khéo léo mang đến sức mạnh tấn công vượt trội.\n- Sử dụng vật liệu HM Graphite cung cấp độ cứng cáp và bền bỉ.', 1, 100, 0),
(2, 'VNB024194', 'Vợt cầu lông Yonex Arcsaber 0 Ability', 579000, '4U5', 1, 1, 'VNB024194.webp', 'Giới thiệu Vợt cầu lông Yonex Arcsaber 0 Ability\n- Phân khúc giá rẻ, chỉ số cân bằng cùng đũa vợt siêu dẻo cho khả năng hỗ trợ lực.\n- Chất liệu Graphite cao cấp đảm bảo về độ bền, công nghệ ISOMETRIC mở rộng điểm ngọt.', 1, 100, 0),
(3, 'VNB024193', 'Vợt cầu lông Yonex Arcsaber 0 Clear', 579000, '4U5', 1, 1, 'VNB024193.webp', 'Giới thiệu Vợt cầu lông Yonex Arcsaber 0 Clear\n- Dòng vợt giá rẻ, cân bằng, dễ kiểm soát. Khung vợt Aero+Box giảm lực cản gió.\n- Phù hợp cho người mới chơi yêu thích lối đánh công thủ toàn diện.', 1, 100, 0),
(4, 'VNB026987', 'Vợt cầu lông Yonex NanoFlare 270 Speed - Purple', 1739000, '4U5', 1, 1, 'VNB026987.webp', 'Giới thiệu Vợt cầu lông Yonex NanoFlare 270 Speed\n- Thiết kế nhẹ đầu, thân cứng trung bình, hướng đến lối chơi tốc độ.\n- Tông màu Tím - Đen nổi bật, hiện đại.', 1, 100, 0),
(5, 'VNB026983', 'Vợt cầu lông Yonex Astrox 22 Lite (BK/RD)', 2349000, '3F5', 1, 1, 'VNB026983.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 22 Lite\n- Siêu nhẹ (3F5), chuyên dụng cho phái nữ hoặc vị trí cầu trước.\n- Công nghệ Rotational Generator System giúp vợt cân bằng dù nặng đầu.', 1, 100, 0),
(6, 'VNB026668', 'Vợt cầu lông Yonex Astrox 100 Tour VA', 4469000, '4U5, 4U6', 1, 1, 'VNB026668.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 100 Tour VA\n- Phiên bản cận cao cấp của 100ZZ, giữ trọn tinh hoa tấn công mạnh mẽ.\n- Công nghệ Namd giúp trục vợt đàn hồi nhanh.', 1, 100, 0),
(7, 'VNB026413', 'Vợt cầu lông Yonex Astrox 100ZZ VA', 5329000, '4U5, 4U6', 1, 1, 'VNB026413.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 100ZZ VA\n- Phiên bản Signature của Viktor Axelsen.\n- Siêu phẩm tấn công với đũa vợt Hyper Slim siêu cứng và đặc.', 1, 100, 0),
(8, 'VNB026653', 'Vợt cầu lông Yonex Astrox 100ZZ VA (Bản 2)', 5329000, '4U5, 4U6', 1, 1, 'VNB026653.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 100ZZ VA (Bản 2)\n- Mẫu vợt chủ lực dòng Astrox, độ cân bằng đầu vợt cao.\n- Thích hợp cho người chơi chuyên nghiệp, lực tay khỏe.', 1, 100, 0),
(9, 'VNB026413_P', 'Vợt cầu lông Yonex Astrox 99 Play 2025', 1769000, '4U5', 1, 1, 'VNB026413.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 99 Play 2025\n- Phiên bản giá rẻ của dòng 99 Pro. Vẫn giữ công nghệ Gen 3 giúp đập cầu cắm sân.\n- Thân dẻo, dễ chơi cho người mới.', 1, 100, 0),
(10, 'VNB026411', 'Vợt cầu lông Yonex Astrox 99 Tour 2025', 4359000, '4U5', 1, 1, 'VNB026411.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 99 Tour 2025\n- Thiết kế lấy cảm hứng từ thiên thạch. Công nghệ Volume Cut Resin tăng độ bền.\n- Phù hợp người chơi bán chuyên.', 1, 100, 0),
(11, 'VNB026410', 'Vợt cầu lông Yonex Astrox 99 Pro 2025', 4959000, '4U5', 1, 1, 'VNB026410.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 99 Pro 2025\n- Siêu phẩm Gen 3 với bộ cản trợ lực đỉnh khung.\n- Dành cho lối đánh đơn, thiên công mạnh mẽ.', 1, 100, 0),
(12, 'VNB024192', 'Vợt cầu lông Yonex Arcsaber 0 Feel', 579000, '4U5', 1, 1, 'VNB024192.webp', 'Giới thiệu Vợt cầu lông Yonex Arcsaber 0 Feel\n- Phân khúc giá rẻ, thân dẻo, dễ điều khiển.\n- Hỗ trợ tốt cho người mới tập chơi.', 1, 100, 0),
(13, 'VNB024012', 'Vợt cầu lông Yonex Nanoflare Junior', 1639000, '4U7', 1, 1, 'VNB024012.webp', 'Giới thiệu Vợt cầu lông Yonex Nanoflare Junior\n- Thiết kế chuyên biệt cho trẻ em hoặc người lực tay yếu.\n- Thân siêu dẻo, trọng lượng nhẹ, dễ dàng vung vợt.', 1, 100, 0),
(14, 'VNB022980', 'Vợt cầu lông Yonex Astrox 01F 2024', 959000, '4U5', 1, 1, 'VNB022980.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 01F 2024\n- Dòng F (Feel) thiên về cảm giác và điều cầu.\n- Trợ lực tốt cho người mới, giá thành hợp lý.', 1, 100, 0),
(15, 'VNB022977', 'Vợt cầu lông Yonex Astrox 01C 2024', 959000, '4U5', 1, 1, 'VNB022977.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 01C 2024\n- Dòng C (Clear) hỗ trợ phông cầu cao sâu.\n- Thân dẻo, đầu hơi nặng giúp tấn công tốt hơn bản F.', 1, 100, 0),
(16, 'VNB022973', 'Vợt cầu lông Yonex Astrox 01A 2024', 959000, '4U5', 1, 1, 'VNB022973.webp', 'Giới thiệu Vợt cầu lông Yonex Astrox 01A 2024\n- Dòng A (Ability) thiên về kỹ năng và phản tạt.\n- Linh hoạt trong các pha cầu đôi công.', 1, 100, 0),
(17, 'VNB022972', 'Vợt cầu lông Yonex Arcsaber 2 Feel', 1259000, '4U5', 1, 1, 'VNB022972.webp', 'Giới thiệu Vợt cầu lông Yonex Arcsaber 2 Feel\n- Dòng vợt kiểm soát cầu (Control).\n- Khung vợt ổn định, giúp điều cầu chính xác vào các góc sân.', 1, 100, 0),
(18, 'VNB022970', 'Vợt cầu lông Yonex Arcsaber 2 Clear', 1259000, '4U5, 4U6', 1, 1, 'VNB022970.webp', 'Giới thiệu Vợt cầu lông Yonex Arcsaber 2 Clear\n- Hỗ trợ phông cầu nhẹ nhàng.\n- Phù hợp cho người chơi phong trào muốn cải thiện lực đánh.', 1, 100, 0),
(19, 'VNB022968', 'Vợt cầu lông Yonex Arcsaber 2 Ability', 1259000, '4U5', 1, 1, 'VNB022968.webp', 'Giới thiệu Vợt cầu lông Yonex Arcsaber 2 Ability\n- Cân bằng giữa công và thủ.\n- Dễ dàng xoay chuyển từ phòng ngự sang tấn công.', 1, 100, 1),
(20, 'VNB021973', 'Vợt cầu lông Yonex Nanoflare 700 Pro 2024', 4650000, '4U5, 5U5', 1, 1, 'VNB021973.webp', 'Giới thiệu Vợt cầu lông Yonex Nanoflare 700 Pro 2024\n- Phiên bản nâng cấp của huyền thoại NF700.\n- Khung vợt Aero Frame xé gió, phản tạt cực nhanh.', 1, 100, 0),
(21, 'VNB025565', 'Vợt cầu lông Lining Axforce 80 Light', 4690000, '4U, 5U', 2, 1, 'VNB025565.webp', 'Giới thiệu Vợt cầu lông Lining Axforce 80 Light\n- Phiên bản nhẹ (4U/5U) của Axforce 80.\n- Tông màu trắng sang trọng. Linh hoạt, tốc độ nhưng vẫn giữ được uy lực.', 1, 100, 0),
(22, 'VNB024860', 'Vợt Lining Axforce 90 New - Loh Kean Yew', 4349000, '3U, 4U, 5U', 2, 1, 'VNB024860.webp', 'Giới thiệu Vợt Lining Axforce 90 New - Loh Kean Yew\n- Phiên bản Rồng Xanh tri ân nhà vô địch thế giới.\n- Thân cứng, nặng đầu, chuyên công.', 1, 100, 0),
(23, 'VNB024848', 'Vợt Lining Axforce BigBang new', 1690000, '4U, 5U', 2, 1, 'VNB024848.webp', 'Giới thiệu Vợt Lining Axforce BigBang New\n- Dòng vợt \"quốc dân\" cho người mới và phái nữ.\n- Màu sắc bắt mắt, thân dẻo, dễ chơi, công nghệ TB Nano bền bỉ.', 1, 100, 0),
(24, 'VNB024442', 'Vợt cầu lông Lining Axforce 30', 1299000, '4U', 2, 1, 'VNB024442.webp', 'Giới thiệu Vợt cầu lông Lining Axforce 30\n- Mẫu vợt tầm trung mới 2025.\n- Hệ thống Sonic Boom cho tiếng nổ vang. Thích hợp lối đánh công thủ toàn diện.', 1, 100, 0),
(25, 'VNB026782', 'Vợt cầu lông Lining Halbertec 1000', 880000, '4U', 2, 1, 'VNB026782.webp', 'Giới thiệu Vợt cầu lông Lining Halbertec 1000\n- Dòng vợt kiểm soát giá rẻ mới nhất.\n- Thân dẻo, dễ thuần, phù hợp học sinh sinh viên.', 1, 100, 0),
(26, 'VNB026781', 'Vợt cầu lông Lining Halbertec Motor Pro', 2100000, '4U', 2, 1, 'VNB026781.webp', 'Giới thiệu Vợt cầu lông Lining Halbertec Motor Pro\n- Phiên bản nâng cấp với vật liệu carbon cao cấp hơn.\n- Ổn định, đầm tay, hỗ trợ điều cầu chính xác.', 1, 100, 0),
(27, 'VNB024963', 'Vợt cầu lông Lining Halbertec 3000', 1100000, '4U', 2, 1, 'VNB024963.webp', 'Giới thiệu Vợt cầu lông Lining Halbertec 3000\n- Thiết kế hiện đại, màu sắc nổi bật.\n- Trục vợt mỏng giúp tăng tốc độ vung vợt.', 1, 100, 0),
(28, 'VNB024856', 'Vợt cầu lông Lining Halbertec 4000', 2070000, '4U', 2, 1, 'VNB024856.webp', 'Giới thiệu Vợt cầu lông Lining Halbertec 4000\n- Cây vợt toàn diện ở phân khúc tầm trung.\n- Hấp thụ sốc tốt, giảm chấn thương cổ tay.', 1, 100, 0),
(29, 'VNB024441', 'Vợt cầu lông Lining Woods N90', 5049000, '4U', 2, 1, 'VNB024441.webp', 'Giới thiệu Vợt cầu lông Lining Woods N90\n- Huyền thoại tấn công của Lin Dan.\n- Nặng đầu, thân cứng, tạo ra những cú smash sấm sét.', 1, 100, 0),
(30, 'VNB023704', 'Vợt cầu lông Lining Aeronaut 6000I', 2190000, '5U', 2, 1, 'VNB023704.webp', 'Giới thiệu Vợt cầu lông Lining Aeronaut 6000I\n- Siêu kiếm 5U chuyên bắt lưới.\n- Đầu siêu nặng (320mm) giúp dứt điểm trên lưới cực nhanh.', 1, 100, 0),
(31, 'VNB023703', 'Vợt cầu lông Lining Aeronaut 6000C', 1950000, '3U', 2, 1, 'VNB023703.webp', 'Giới thiệu Vợt cầu lông Lining Aeronaut 6000C\n- Phiên bản thiên công của dòng 6000 (Combat).\n- Rãnh thoát khí giúp vung vợt nhanh hơn.', 1, 100, 0),
(32, 'VNB023702', 'Vợt cầu lông Lining Aeronaut 6000', 1950000, '3U', 2, 1, 'VNB023702.webp', 'Giới thiệu Vợt cầu lông Lining Aeronaut 6000\n- Bản cân bằng, dễ chơi nhất dòng 6000.\n- Phù hợp đánh đôi, công thủ nhịp nhàng.', 1, 100, 0),
(33, 'VNB020543', 'Vợt cầu lông Lining Axforce Cannon', 980000, '4U5, 5U5', 2, 1, 'VNB020543.webp', 'Giới thiệu Vợt cầu lông Lining Axforce Cannon\n- \"Khẩu đại bác\" giá rẻ. Hơi nặng đầu, thân cứng trung bình.\n- Lựa chọn số 1 cho người mới thích tấn công.', 1, 100, 0),
(34, 'VNB020685', 'Vợt cầu lông Lining Bladex 500', 2250000, '3U, 4U', 2, 1, 'VNB020685.webp', 'Giới thiệu Vợt cầu lông Lining Bladex 500\n- Chuyên về phản tạt và tốc độ.\n- Khung vát cạnh sắc bén, giảm sức cản không khí tối đa.', 1, 100, 0),
(35, 'VNB021999', 'Vợt cầu lông Lining Calibar 900', 4790000, '3U, 4U', 2, 1, 'VNB021999.webp', 'Giới thiệu Vợt cầu lông Lining Calibar 900\n- Dòng vợt 3D Calibar cao cấp.\n- Khung vợt vát cạnh kim cương, thiên công mạnh mẽ.', 1, 100, 0),
(36, 'VNB021995', 'Vợt cầu lông Lining Calibar 900B', 4690000, '3U, 4U', 2, 1, 'VNB021995.webp', 'Giới thiệu Vợt cầu lông Lining Calibar 900B\n- Phiên bản Boost thiên về toàn diện.\n- Thân cứng, đầu đầm, xử lý cầu linh hoạt.', 1, 100, 0),
(37, 'VNB026991', 'Vợt Victor DriveX 12 O Zheng Siwei', 3800000, '3U', 3, 1, 'VNB026991.webp', 'Giới thiệu Vợt Victor DriveX 12 O Zheng Siwei\n- Vợt chuyên dụng của ZSW.\n- Công nghệ Free Core tay cầm nhựa giảm rung. Kiểm soát cầu đỉnh cao.', 1, 100, 0),
(38, 'VNB026283', 'Victor Ryuga II Pro China Open 2025', 3950000, '3U', 3, 1, 'VNB026283.webp', 'Giới thiệu Victor Ryuga II Pro China Open\n- Phiên bản giới hạn Rồng Đen.\n- Công nghệ WES 2.0 tăng cường độ nảy và cắm cầu khi đập.', 1, 100, 0),
(39, 'VNB025848', 'Vợt Victor TK Ultramanz', 1790000, '4U', 3, 1, 'VNB025848.webp', 'Giới thiệu Vợt Victor TK Ultramanz\n- Hợp tác với Ultraman Z.\n- Thiết kế độc đáo, thiên công, phù hợp người chơi trẻ.', 1, 100, 0),
(40, 'VNB026992', 'Victor Thruster F C Ultra X', 4200000, '3U', 3, 1, 'VNB026992.webp', 'Giới thiệu Victor Thruster F C Ultra X\n- Dòng vợt của Tai Tzu Ying.\n- Đũa vợt siêu mỏng, đàn hồi tốt, đánh cầu cắm và hiểm.', 1, 100, 0),
(41, 'VNB026962', 'Victor Auraspeed FANTÔME F HYQ', 4490000, '4U', 3, 1, 'VNB026962.webp', 'Giới thiệu Victor Auraspeed FANTÔME F HYQ\n- Vợt của Huang Ya Qiong.\n- Siêu nhẹ, siêu nhanh, chuyên bắt lưới và phản tạt.', 1, 100, 0),
(42, 'VNB026132', 'Vợt cầu lông Victor DX-1 A', 1450000, '4U', 3, 1, 'VNB026132.webp', 'Giới thiệu Vợt Victor DX-1 A\n- Màu trắng ngọc trai tuyệt đẹp.\n- Dòng DriveX cân bằng, dễ chơi, kiểm soát tốt.', 1, 100, 0),
(43, 'VNB025849', 'Vợt Victor Ars Alien Baltan', 1290000, '4U', 3, 1, 'VNB025849.webp', 'Giới thiệu Vợt Victor Ars Alien Baltan\n- Lấy cảm hứng từ quái vật Baltan.\n- Thiết kế lạ mắt, thuộc dòng tốc độ Auraspeed.', 1, 100, 0),
(44, 'VNB025847', 'Vợt Victor TK Ultramantiga', 2490000, '4U', 3, 1, 'VNB025847.webp', 'Giới thiệu Vợt Victor TK Ultramantiga\n- Cảm hứng từ Ultraman Tiga.\n- Dòng Thruster thiên công, sức mạnh hủy diệt.', 1, 100, 0),
(45, 'VNB025623', 'Victor Thruster Shenron G – DragonBall Z', 3200000, '4U', 3, 1, 'VNB025623.webp', 'Giới thiệu Victor Thruster Shenron G\n- Phiên bản Rồng Thần Dragon Ball.\n- Màu xanh lá, chi tiết ngọc rồng 7 sao cực chất.', 1, 100, 0),
(46, 'VNB025622', 'Set Victor Thruster Goku GB F', 4800000, '4U', 3, 1, 'VNB025622.webp', 'Giới thiệu Victor Thruster Goku GB F\n- Phiên bản Songoku.\n- Màu cam xanh đặc trưng. Sức mạnh Saiyan trên sân cầu.', 1, 100, 0),
(47, 'VNB025429', 'Victor Auraspeed Fantôme SC25', 4150000, '4U', 3, 1, 'VNB025429.webp', 'Giới thiệu Victor Auraspeed Fantôme SC25\n- Siêu phẩm 2025.\n- Khung vợt Aero Sword giảm tối đa sức cản gió.', 1, 100, 0),
(48, 'VNB025374', 'Vợt Victor Thruster HMR Pro', 1390000, '4U', 3, 1, 'VNB025374.webp', 'Giới thiệu Vợt Victor Thruster HMR Pro\n- \"Búa thần\" giá rẻ.\n- Nặng đầu, đập cầu uy lực, phù hợp người mới thích tấn công.', 1, 100, 0),
(49, 'VNB025347', 'Vợt Victor Ryuga TD/C', 2450000, '4U', 3, 1, 'VNB025347.webp', 'Giới thiệu Vợt Victor Ryuga TD/C\n- Bản rút gọn của Ryuga 1.\n- Dễ thuần hơn, vẫn giữ được chất \"đầm\" của dòng Rồng.', 1, 100, 0),
(50, 'VNB025143', 'Victor Thruster Ryuga II Pro CPS', 3950000, '4U', 3, 1, 'VNB025143.webp', 'Giới thiệu Victor Ryuga II Pro CPS\n- Phối màu mới Tím/Hồng.\n- Nâng cấp vật liệu carbon giúp khung vợt cứng cáp hơn.', 1, 100, 0),
(51, 'VNB025142', 'Victor Thruster Ryuga Metallic CPS', 3950000, '4U', 3, 1, 'VNB025142.webp', 'Giới thiệu Victor Ryuga Metallic CPS\n- Khung vợt có lớp kim loại (Metallic).\n- Cảm giác cầu đầm, chắc chắn, tiếng nổ đã tai.', 1, 100, 0),
(52, 'VNB024938', 'Victor Auraspeed 90k II TD', 3100000, '4U', 3, 1, 'VNB024938.webp', 'Giới thiệu Victor Auraspeed 90k II TD\n- Bản tầm trung của 90K II.\n- Tốc độ nhanh, phản tạt tốt, giá thành hợp lý.', 1, 100, 0),
(53, 'VNB024937', 'Victor Thruster Ryuga II TD', 3100000, '4U', 3, 1, 'VNB024937.webp', 'Giới thiệu Victor Thruster Ryuga II TD\n- Bản tầm trung của Ryuga II.\n- Vẫn giữ được độ nặng đầu để smash tốt.', 1, 100, 0),
(54, 'VNB024722', 'Victor Auraspeed 100X Ultra G 2025', 3700000, '3U, 4U', 3, 1, 'VNB024722.webp', 'Giới thiệu Victor Auraspeed 100X Ultra G\n- Đũa vợt siêu mỏng 5.8mm.\n- Tốc độ vung vợt nhanh nhất thế giới hiện nay.', 1, 100, 0),
(55, 'VNB024052', 'Vợt cầu lông Victor TK 220H II', 1350000, '3U, 4U', 3, 1, 'VNB024052.webp', 'Giới thiệu Vợt Victor TK 220H II\n- Dòng vợt chịu lực căng cao (16.5kg).\n- Bền bỉ, trâu bò, thích hợp đánh đôi va chạm.', 1, 100, 0),
(56, 'VNB026282', 'Vợt Mizuno Prototype X-3D', 2629000, '4U', 4, 1, 'VNB026282.webp', 'Giới thiệu Mizuno Prototype X-3D\n- Khung vợt 3D vát cạnh độc đáo.\n- Giảm sức cản gió, tăng tốc độ vung vợt đáng kể.', 1, 100, 0),
(57, 'VNB025845', 'Vợt Mizuno Carbo Pro 839', 1040000, '4U', 4, 1, 'VNB025845.webp', 'Giới thiệu Mizuno Carbo Pro 839\n- Dòng vợt cân bằng, dễ chơi.\n- Khung Aero Hexagram giảm rung, ổn định mặt vợt.', 1, 100, 0),
(58, 'VNB025844', 'Vợt Mizuno Carbo Pro 837', 1040000, '4U', 4, 1, 'VNB025844.webp', 'Giới thiệu Mizuno Carbo Pro 837\n- Màu cam năng động.\n- Thân cứng trung bình, công thủ toàn diện.', 1, 100, 0),
(59, 'VNB025843', 'Vợt Mizuno Carbo Pro 835', 1040000, '4U', 4, 1, 'VNB025843.webp', 'Giới thiệu Mizuno Carbo Pro 835\n- Màu xanh Neon bắt mắt.\n- Hơi nặng đầu, hỗ trợ tấn công tốt.', 1, 100, 0),
(60, 'VNB026291', 'Vợt Mizuno JPX 3.3 Rage', 1416000, '4U', 4, 1, 'VNB026291.webp', 'Giới thiệu Mizuno JPX 3.3 Rage\n- Dòng trung cấp mới 2025.\n- Thiết kế chắc chắn, chịu lực căng cao.', 1, 100, 0),
(61, 'VNB026281', 'Vợt Mizuno Prototype X-1I', 3236000, '4U', 4, 1, 'VNB026281.webp', 'Giới thiệu Mizuno Prototype X-1I\n- Vợt cao cấp màu đỏ.\n- Kiểm soát cầu tốt, thích hợp đánh cầu bền.', 1, 100, 0),
(62, 'VNB026065', 'Vợt Mizuno Caliber S-Pro', 2542000, '4U', 4, 1, 'VNB026065.webp', 'Giới thiệu Mizuno Caliber S-Pro\n- Dòng vợt thiên về kỹ thuật (Skill).\n- Giúp người chơi thực hiện các pha bỏ nhỏ, cắt cầu chính xác.', 1, 100, 0),
(63, 'VNB026064', 'Vợt Mizuno Caliber S-Boost', 1849000, '4U', 4, 1, 'VNB026064.webp', 'Giới thiệu Mizuno Caliber S-Boost\n- Tăng cường lực đẩy cầu.\n- Giúp phông cầu nhẹ nhàng hơn, đỡ tốn sức.', 1, 100, 0),
(64, 'VNB025934', 'Vợt Mizuno Altrax 82', 1849000, '4U', 4, 1, 'VNB025934.webp', 'Giới thiệu Mizuno Altrax 82\n- Khung vợt xoắn độc đáo.\n- Tăng lực momen xoắn, giúp cú đập cầu xoáy và hiểm hơn.', 1, 100, 0),
(65, 'VNB025846', 'Vợt Mizuno BDSS Altius Sonic', 3756000, '4U', 4, 1, 'VNB025846.webp', 'Giới thiệu Mizuno BDSS Altius Sonic\n- Sản xuất tại Nhật Bản (Made in Japan).\n- Cảm giác cầu mềm mại, giữ cầu tốt.', 1, 100, 0),
(66, 'VNB025842', 'Vợt Mizuno Powerblade 597', 1040000, '4U', 4, 1, 'VNB025842.webp', 'Giới thiệu Mizuno Powerblade 597\n- Vợt siêu nhẹ, có thể nổi trên mặt nước.\n- Phù hợp người lực tay yếu, người lớn tuổi.', 1, 100, 0),
(67, 'VNB025841', 'Vợt Mizuno Razorblade 509', 1040000, '4U', 4, 1, 'VNB025841.webp', 'Giới thiệu Mizuno Razorblade 509\n- Thiết kế đen tím huyền bí.\n- Tấn công nhanh, chớp nhoáng.', 1, 100, 0),
(68, 'VNB025053', 'Vợt Mizuno Fortius 10 BDSS', 4900000, '4U', 4, 1, 'VNB025053.webp', 'Giới thiệu Mizuno Fortius 10 BDSS\n- Vợt chuyên công của Hendra Setiawan.\n- Đập cầu cực mạnh, đầm tay.', 1, 100, 0),
(69, 'VNB025049', 'Vợt Mizuno Acrospeed 01 Drive', 4900000, '4U', 4, 1, 'VNB025049.webp', 'Giới thiệu Mizuno Acrospeed 01 Drive\n- Công nghệ Torque Technology truyền lực tối đa.\n- Nhanh, mạnh, chính xác.', 1, 100, 0),
(70, 'VNB025045', 'Vợt Mizuno JPX 8.1 Pro', 2022000, '4U', 4, 1, 'VNB025045.webp', 'Giới thiệu Mizuno JPX 8.1 Pro\n- Vợt vát cạnh, thiên công.\n- Cần lực tay tốt để thuần phục.', 1, 100, 0),
(71, 'VNB025037', 'Vợt Mizuno JPX 8.2', 1820000, '4U', 4, 1, 'VNB025037.webp', 'Giới thiệu Mizuno JPX 8.2\n- Dễ chơi hơn bản 8.1.\n- Công thủ toàn diện, linh hoạt.', 1, 100, 0),
(72, 'VNB023981', 'Vợt Mizuno Acrospeed 8', 3150000, '4U', 4, 1, 'VNB023981.webp', 'Giới thiệu Mizuno Acrospeed 8\n- Thiên về tốc độ và phản tạt.\n- Thân dẻo, trợ lực tốt.', 1, 100, 0),
(73, 'VNB023980', 'Vợt Mizuno Acrospeed 7', 3150000, '5U', 4, 1, 'VNB023980.webp', 'Giới thiệu Mizuno Acrospeed 7\n- Bản 5U siêu nhẹ.\n- Dành cho người mới tập chơi, nữ giới.', 1, 100, 0),
(74, 'VNB022305', 'Vợt Mizuno Altius 5.1 Kinryũ', 3640000, '4U', 4, 1, 'VNB022305.webp', 'Giới thiệu Mizuno Altius 5.1 Kinryũ\n- Họa tiết Rồng Vàng.\n- Vợt cứng, nặng đầu, dành cho người tay khỏe.', 1, 100, 0),
(75, 'VNB022304', 'Vợt Mizuno XYST 07', 3033000, '4U', 4, 1, 'VNB022304.webp', 'Giới thiệu Mizuno XYST 07\n- Vợt thiên công giá tầm trung.\n- Đập cầu tốt, đầm tay.', 1, 95, 2),
(76, 'VNB025972', 'Giày Kawasaki K32012', 1000000, 'Size 36-44', 5, 2, 'VNB025972.webp', 'Giới thiệu Giày cầu lông Kawasaki K32012\n- Mẫu giày toàn diện, bám sân tốt.\n- Hệ thống lỗ thoáng khí giúp chân luôn khô ráo.', 1, 100, 0),
(77, 'VNB025971', 'Giày Kawasaki 32011', 980000, 'Size 36-44', 5, 2, 'VNB025971.webp', 'Giới thiệu Giày Kawasaki 32011\n- Giày giá rẻ bền bỉ.\n- Da bóng dễ vệ sinh, đế chống mài mòn.', 1, 100, 0),
(78, 'VNB025130', 'Giày Kawasaki 065', 880000, 'Size 36-44', 5, 2, 'VNB025130.webp', 'Giới thiệu Giày Kawasaki 065\n- Đế cao su tự nhiên bám sân tốt.\n- Phù hợp chơi sân bê tông, sân gạch.', 1, 96, 0),
(79, 'VNB023646', 'Giày Kawasaki 3338', 1140000, 'Size 36-44', 5, 2, 'VNB023646.webp', 'Giới thiệu Giày Kawasaki 3338\n- Thiết kế màu sắc bắt mắt.\n- Form giày ôm chân, chắc chắn.', 1, 100, 0),
(80, 'VNB021291', 'Giày Kawasaki 3307', 880000, 'Size 36-44', 5, 2, 'VNB021291.webp', 'Giới thiệu Giày Kawasaki 3307\n- Tấm xoắn carbon chống lật cổ chân.\n- Hệ thống thoát khí tuần hoàn.', 1, 99, 0),
(81, 'VNB023207', 'Giày Kawasaki K367', 1350000, 'Size 36-44', 5, 2, 'VNB023207.webp', 'Giới thiệu Giày Kawasaki K367\n- Cổ giày ôm khít bảo vệ mắt cá.\n- Đế có đệm khí giảm chấn thương.', 1, 100, 0),
(82, 'VNB023193', 'Giày Kawasaki 3306', 1090000, 'Size 36-44', 5, 2, 'VNB023193.webp', 'Giới thiệu Giày Kawasaki 3306\n- Họa tiết ngụy trang độc đáo.\n- Đệm Power Cushion hấp thụ sốc.', 1, 100, 0),
(83, 'VNB023176', 'Giày Kawasaki 3308', 990000, 'Size 36-44', 5, 2, 'VNB023176.webp', 'Giới thiệu Giày Kawasaki 3308\n- 3 phiên bản màu sắc thời trang.\n- Đế bền, chịu mài mòn tốt.', 1, 100, 0),
(84, 'VNB023165', 'Giày Kawasaki 3324', 750000, 'Size 36-44', 5, 2, 'VNB023165.webp', 'Giới thiệu Giày Kawasaki 3324\n- Màu trắng hồng nữ tính.\n- Form nhỏ gọn, ôm chân.', 1, 100, 0),
(85, 'VNB023163', 'Giày Kawasaki 3322', 1040000, 'Size 36-44', 5, 2, 'VNB023163.webp', 'Giới thiệu Giày Kawasaki 3322\n- Thiết kế trẻ trung, năng động.\n- Giá rẻ, phù hợp học sinh sinh viên.', 1, 100, 0),
(86, 'VNB023162', 'Giày Kawasaki 3321', 1390000, 'Size 36-44', 5, 2, 'VNB023162.webp', 'Giới thiệu Giày Kawasaki 3321\n- Chất liệu Fly-Knit siêu nhẹ.\n- Di chuyển thanh thoát, không bị nặng chân.', 1, 100, 0),
(87, 'VNB023148', 'Giày Kawasaki K3326', 990000, 'Size 36-44', 5, 2, 'VNB023148.webp', 'Giới thiệu Giày Kawasaki K3326\n- Màu trắng xanh hiện đại.\n- Da PU bền bỉ, chống thấm nước.', 1, 100, 0),
(88, 'VNB023146', 'Giày Kawasaki K3328', 1050000, 'Size 36-44', 5, 2, 'VNB023146.webp', 'Giới thiệu Giày Kawasaki K3328\n- Chống bám bẩn hiệu quả.\n- Lót giày êm ái, thấm hút mồ hôi.', 1, 100, 0),
(89, 'VNB023134', 'Giày Kawasaki K3333', 940000, 'Size 36-44', 5, 2, 'VNB023134.webp', 'Giới thiệu Giày Kawasaki K3333\n- Chất liệu mềm mại.\n- Cảm giác thật chân khi di chuyển.', 1, 100, 0),
(90, 'VNB023128', 'Giày Kawasaki 3327', 900000, 'Size 36-44', 5, 2, 'VNB023128.webp', 'Giới thiệu Giày Kawasaki 3327\n- Logo Panda dễ thương.\n- Phong cách cá tính, khác biệt.', 1, 100, 0),
(91, 'VNB023096', 'Giày Kawasaki K3329', 890000, 'Size 36-44', 5, 2, 'VNB023096.webp', 'Giới thiệu Giày Kawasaki K3329\n- Giá siêu rẻ.\n- Bền bỉ, cày sân bê tông thoải mái.', 1, 100, 0),
(92, 'VNB023095', 'Giày Kawasaki K3405', 1340000, 'Size 36-44', 5, 2, 'VNB023095.webp', 'Giới thiệu Giày Kawasaki K3405\n- Công nghệ đế TUFF RB siêu bền.\n- Sợi carbon chống xoắn bảo vệ chân.', 1, 100, 0),
(93, 'VNB016823', 'Giày Kawasaki 2301 Tím Nhạt', 1140000, 'Size 36-44', 5, 2, 'VNB016823.webp', 'Giới thiệu Giày Kawasaki 2301\n- Màu tím nhạt nhẹ nhàng.\n- Chất liệu tổng hợp cao cấp.', 1, 100, 0),
(94, 'VNB015779', 'Giày Kawasaki 3303 Đỏ', 1790000, 'Size 36-44', 5, 2, 'VNB015779.webp', 'Giới thiệu Giày Kawasaki 3303 Đỏ\n- Màu đỏ vàng quyền lực.\n- Dòng cao cấp, êm ái, bám sân.', 1, 100, 0),
(95, 'VNB015778', 'Giày Kawasaki 3303 Đen', 1790000, 'Size 36-44', 5, 2, 'VNB015778.webp', 'Giới thiệu Giày Kawasaki 3303 Đen\n- Màu đen huyền bí.\n- Chất liệu Microfiber cao cấp.', 1, 100, 0),
(96, 'VNB027031', 'Giày Yonex Cascade Drive 3', 2450000, 'Size 36-44', 1, 2, 'VNB027031.webp', 'Giới thiệu Giày Yonex Cascade Drive 3\n- Dòng giày êm ái nhất của Yonex.\n- Công nghệ Power Cushion bảo vệ đầu gối.', 1, 100, 0),
(97, 'VNB026984', 'Giày Yonex Rapio', 989000, 'Size 36-44', 1, 2, 'VNB026984.webp', 'Giới thiệu Giày Yonex Rapio\n- Giày tầm trung ngon bổ rẻ.\n- Nhẹ, thoáng khí, form chuẩn.', 1, 100, 0),
(98, 'VNB026977', 'Giày Yonex Dominant 6', 979000, 'Size 36-44', 1, 2, 'VNB026977.webp', 'Giới thiệu Giày Yonex Dominant 6\n- Mang công nghệ cao cấp xuống bình dân.\n- Đế bám sân, di chuyển linh hoạt.', 1, 100, 0),
(99, 'VNB026740', 'Giày Yonex SHB 65X VA', 1809000, 'Size 36-44', 1, 2, 'VNB026740.webp', 'Giới thiệu Giày Yonex SHB 65X VA\n- Phiên bản Viktor Axelsen.\n- Ổn định, chắc chắn, màu sắc đẹp.', 1, 100, 0),
(100, 'VNB026444', 'Giày Yonex Hexis', 699000, 'Size 36-44', 1, 2, 'VNB026444.webp', 'Giới thiệu Giày Yonex Hexis\n- Mẫu mới 2025.\n- Thiết kế đơn giản, tinh tế.', 1, 100, 0),
(101, 'VNB026794', 'Giày Lining AYZV001-3', 2190000, 'Size 36-44', 2, 2, 'VNB026794.webp', 'Giới thiệu Giày Lining AYZV001-3\n- Thiết kế siêu nhẹ, hỗ trợ bật nhảy.\n- Màu sắc hiện đại, trẻ trung.', 1, 100, 0),
(102, 'VNB026761', 'Giày Lining AYTU001-9', 1200000, 'Size 36-44', 2, 2, 'VNB026761.webp', 'Giới thiệu Giày Lining AYTU001-9\n- Phong cách tối giản.\n- Mũi giày chống va đập bảo vệ ngón chân.', 1, 100, 0),
(103, 'VNB025073', 'Giày Lining AYTU001-7', 1200000, 'Size 36-44', 2, 2, 'VNB025073.webp', 'Giới thiệu Giày Lining AYTU001-7\n- Phối màu trắng đen sang trọng.\n- Chất liệu da PU dễ vệ sinh.', 1, 99, 0),
(104, 'VNB026956', 'Giày Lining AYTV031-1', 1150000, 'Size 36-44', 2, 2, 'VNB026956.webp', 'Giới thiệu Giày Lining AYTV031-1\n- Đế cao su chống mài mòn.\n- Bám sân tốt, chống trơn trượt.', 1, 100, 0),
(105, 'VNB027179', 'Giày Victor A970 cADV/B', 2570000, 'Size 36-44', 3, 2, 'VNB027179.webp', 'Giới thiệu Giày Victor A970 cADV/B\n- Công nghệ NitroLite hấp thụ sốc.\n- Bảo vệ gót chân tối đa.', 1, 100, 0),
(106, 'VNB026970', 'Giày Victor A550 LS', 1480000, 'Size 36-44', 3, 2, 'VNB026970.webp', 'Giới thiệu Giày Victor A550 LS\n- Form U-Shape cho bàn chân bè.\n- Rất thoải mái cho người chân to ngang.', 1, 100, 0),
(107, 'VNB026969', 'Giày Victor A970 NL-A', 2380000, 'Size 36-44', 3, 2, 'VNB026969.webp', 'Giới thiệu Giày Victor A970 NL-A\n- Màu trắng thời thượng.\n- Công nghệ hỗ trợ gót chân 3D.', 1, 100, 0),
(108, 'VNB026968', 'Giày Victor A170 II-LR', 1080000, 'Size 36-44', 3, 2, 'VNB026968.webp', 'Giới thiệu Giày Victor A170 II-LR\n- Thiết kế năng động.\n- Lưới thoáng khí giúp chân không bị bí.', 1, 100, 0),
(109, 'VNB027180', 'Giày Victor A362 LT/AC', 1250000, 'Size 36-44', 3, 2, 'VNB027180.webp', 'Giới thiệu Giày Victor A362 LT/AC\n- Giày tầm trung nhẹ và êm.\n- Cổ giày gia cố chắc chắn.', 1, 95, 0),
(110, 'VNB027271', 'Áo Yonex TRL2768 - Oatmeal', 319000, 'S,M,L', 1, 3, 'VNB027271.webp', 'Áo cầu lông Yonex TRL2768\n- Chất liệu vải mè mưa thoáng mát.\n- Thấm hút mồ hôi nhanh, không bết dính.', 1, 100, 0),
(111, 'VNB027269', 'Áo Yonex TRL2768 - Blue', 319000, 'S,M,L', 1, 3, 'VNB027269.webp', 'Áo cầu lông Yonex TRL2768 Blue\n- Màu xanh mát mắt.\n- Form áo chuẩn, tôn dáng thể thao.', 1, 100, 0),
(112, 'VNB027265', 'Áo Yonex TRL2771', 329000, 'S,M,L', 1, 3, 'VNB027265.webp', 'Áo cầu lông Yonex TRL2771\n- Thiết kế vạt áo xẻ tà năng động.\n- Dễ dàng vận động, smash cầu.', 1, 100, 0),
(113, 'VNB027169', 'Áo Yonex TRM3066 - Peach', 119000, 'S,M,L', 1, 3, 'VNB027169.webp', 'Áo cầu lông Yonex TRM3066 Peach\n- Màu cam đào trẻ trung.\n- Vải co giãn 4 chiều thoải mái.', 1, 100, 0),
(114, 'VNB027232', 'Áo Yonex TRM3066 - White', 119000, 'S,M,L', 1, 3, 'VNB027232.webp', 'Áo cầu lông Yonex TRM3066 White\n- Màu trắng tinh tế.\n- Phù hợp phối với mọi loại quần.', 1, 100, 0),
(115, 'VNB025426', 'Áo Lining 25005 nữ', 160000, 'S,M,L', 2, 3, 'VNB025426.webp', 'Áo cầu lông Lining 25005 Nữ\n- Áo chuyển nhiệt giá rẻ.\n- Họa tiết in 3D sắc nét, không phai màu.', 1, 100, 0),
(116, 'VNB025424', 'Áo Lining 6613 nữ', 160000, 'S,M,L', 2, 3, 'VNB025424.webp', 'Áo cầu lông Lining 6613 Nữ\n- Form nữ ôm eo nhẹ.\n- Chất vải mát, nhẹ, nhanh khô.', 1, 100, 0),
(117, 'VNB025423', 'Áo Lining 6613 nam', 160000, 'S,M,L', 2, 3, 'VNB025423.webp', 'Áo cầu lông Lining 6613 Nam\n- Form nam rộng rãi thoải mái.\n- Thấm hút mồ hôi tốt.', 1, 100, 0),
(118, 'VNB025422', 'Áo Lining 3175 nữ', 160000, 'S,M,L', 2, 3, 'VNB025422.webp', 'Áo cầu lông Lining 3175 Nữ\n- Thiết kế cổ tim nữ tính.\n- Màu sắc trang nhã.', 1, 100, 0),
(119, 'VNB025517', 'Áo Lining P-AHST347-3', 290000, 'S,M,L', 2, 3, 'VNB025517.webp', 'Áo cầu lông Lining P-AHST347\n- Áo chính hãng 100% Polyester.\n- Bền bỉ, giặt máy không lo nhăn.', 1, 100, 0),
(120, 'VNB025478', 'Áo Victor 2115 Nam', 160000, 'S,M,L', 3, 3, 'VNB025478.webp', 'Áo Victor 2115 Nam\n- Áo chuyển nhiệt Victor.\n- Màu sắc bắt mắt, nổi bật trên sân.', 1, 100, 0),
(121, 'VNB025477', 'Áo Victor 2115 Nữ', 160000, 'S,M,L', 3, 3, 'VNB025477.webp', 'Áo Victor 2115 Nữ\n- Áo đôi với mẫu nam.\n- Phù hợp cho các cặp đôi đánh cầu.', 1, 100, 0),
(122, 'VNB025476', 'Áo Victor 2115 Nam Tím', 160000, 'S,M,L', 3, 3, 'VNB025476.webp', 'Áo Victor 2115 Nam Tím\n- Màu tím mộng mơ.\n- Thiết kế độc đáo, cá tính.', 1, 100, 0),
(123, 'VNB025475', 'Áo Victor 2115 Nữ Tím', 160000, 'S,M,L', 3, 3, 'VNB025475.webp', 'Áo Victor 2115 Nữ Tím\n- Form nữ màu tím.\n- Dễ thương, năng động.', 1, 100, 0),
(124, 'VNB026097', 'Áo Victor AT-7500M', 250000, 'S,M,L', 3, 3, 'VNB026097.webp', 'Áo Victor AT-7500M\n- Áo chính hãng Victor.\n- Chất liệu cao cấp, đường may tỉ mỉ.', 1, 89, 0),
(125, 'VNB027172', 'Quần Yonex TSM3064', 139000, 'S,M,L', 1, 4, 'VNB027172.webp', 'Quần Yonex TSM3064\n- Quần short 2 lớp.\n- Co giãn tốt, thoải mái di chuyển.', 1, 100, 0),
(126, 'VNB027171', 'Quần Yonex TSM3064 Blue', 139000, 'S,M,L', 1, 4, 'VNB027171.webp', 'Quần Yonex TSM3064 Blue\n- Màu xanh dễ phối áo.\n- Có túi sâu đựng đồ cá nhân.', 1, 99, 0),
(127, 'VNB026884', 'Quần Yonex TSM3085', 139000, 'S,M,L', 1, 4, 'VNB026884.webp', 'Quần Yonex TSM3085\n- Quần đen cơ bản.\n- Lưng thun có dây rút điều chỉnh.', 1, 100, 0),
(128, 'VNB026883', 'Quần Yonex TSM3085 Poppy', 139000, 'S,M,L', 1, 4, 'VNB026883.webp', 'Quần Yonex TSM3085 Poppy\n- Màu hạt anh túc lạ mắt.\n- Vải nhẹ, không tích điện.', 1, 100, 0),
(129, 'VNB027229', 'Quần Yonex TSM3086', 139000, 'S,M,L', 1, 4, 'VNB027229.webp', 'Quần Yonex TSM3086\n- Thiết kế xẻ tà bên hông.\n- Giúp bước chân linh hoạt hơn.', 1, 100, 0),
(130, 'VNB025641', 'Quần Lining 967 Navy', 130000, 'S,M,L', 2, 4, 'VNB025641.webp', 'Quần Lining 967 Navy\n- Quần vải dù bền bỉ.\n- Không bám bụi, dễ giặt sạch.', 1, 100, 0),
(131, 'VNB025640', 'Quần Lining 967 Trắng', 130000, 'S,M,L', 2, 4, 'VNB025640.webp', 'Quần Lining 967 Trắng\n- Màu trắng sang trọng.\n- Cần giữ gìn cẩn thận khi mặc.', 1, 100, 0),
(132, 'VNB025639', 'Quần Lining 967 Đen', 130000, 'S,M,L', 2, 4, 'VNB025639.webp', 'Quần Lining 967 Đen\n- Màu đen quốc dân.\n- Phù hợp với mọi loại áo.', 1, 100, 0),
(133, 'VNB015140', 'Quần Lining 92009', 130000, 'S,M,L', 2, 4, 'VNB015140.webp', 'Quần Lining 92009\n- Màu xanh ngọc trẻ trung.\n- Chất vải mềm mại.', 1, 100, 0),
(134, 'VNB019196', 'Quần Lining Q37', 110000, 'S,M,L', 2, 4, 'VNB019196.webp', 'Quần Lining Q37\n- Quần nam giá rẻ.\n- Form rộng rãi, thoáng mát.', 1, 100, 0),
(135, 'VNB025687', 'Quần Victor 621 Trắng', 130000, 'S,M,L', 3, 4, 'VNB025687.webp', 'Quần Victor 621 Trắng\n- Quần thi đấu chuyên nghiệp.\n- Thoát mồ hôi cực nhanh.', 1, 100, 0),
(136, 'VNB025654', 'Quần Victor 621 Đen', 130000, 'S,M,L', 3, 4, 'VNB025654.webp', 'Quần Victor 621 Đen\n- Bền bỉ, không phai màu.\n- Logo Victor in sắc nét.', 1, 100, 0),
(137, 'VNB020201', 'Quần Victor 960', 130000, 'S,M,L', 3, 4, 'VNB020201.webp', 'Quần Victor 960\n- Mẫu quần phổ biến nhất.\n- Giá thành hợp lý, chất lượng tốt.', 1, 99, 0),
(138, 'VNB025689', 'Quần Victor 225', 130000, 'S,M,L', 3, 4, 'VNB025689.webp', 'Quần Victor 225\n- Màu hồng cá tính.\n- Dành cho người thích sự nổi bật.', 1, 88, 0),
(139, 'VNB026763', 'Balo Yonex BA92412BEX', 2259000, 'Tiêu chuẩn', 1, 5, 'VNB026763.webp', 'Balo Yonex BA92412BEX\n- Balo cao cấp xanh rêu.\n- Ngăn vợt, giày riêng biệt. Chống thấm nước.', 1, 100, 0),
(140, 'VNB025983', 'Balo Yonex BAG225B1312', 749000, 'Tiêu chuẩn', 1, 5, 'VNB025983.webp', 'Balo Yonex BAG225B1312\n- Ngăn chứa siêu rộng.\n- Đựng vừa laptop, quần áo, vợt thoải mái.', 1, 100, 0),
(141, 'VNB025908', 'Balo Yonex BAG324B2012', 479000, 'Tiêu chuẩn', 1, 5, 'VNB025908.webp', 'Balo Yonex BAG324B2012\n- Thiết kế nhỏ gọn.\n- Phù hợp mang ít đồ, đi tập nhẹ nhàng.', 1, 100, 0),
(142, 'VNB025903', 'Balo Yonex BAG324B1512', 479000, 'Tiêu chuẩn', 1, 5, 'VNB025903.webp', 'Balo Yonex BAG324B1512\n- Kiểu dáng hiện đại.\n- Màu sắc trang nhã, lịch sự.', 1, 100, 0),
(143, 'VNB025898', 'Balo Yonex BAG324B1012', 479000, 'Tiêu chuẩn', 1, 5, 'VNB025898.webp', 'Balo Yonex BAG324B1012\n- Chất liệu Polyester nhẹ.\n- Dễ dàng vệ sinh, lau chùi.', 1, 100, 0),
(144, 'VNB026447', 'Balo Lining P-ABSV133-3', 879000, 'Tiêu chuẩn', 2, 5, 'VNB026447.webp', 'Balo Lining P-ABSV133-3\n- Phối màu trắng xanh navy.\n- Phong cách Unisex, trẻ trung.', 1, 100, 0),
(145, 'VNB026441', 'Balo Lining P-ABSV131-1', 1100000, 'Tiêu chuẩn', 2, 5, 'VNB026441.webp', 'Balo Lining P-ABSV131-1\n- Form cứng cáp, đứng dáng.\n- Bảo vệ vợt tối ưu khỏi va đập.', 1, 100, 0),
(146, 'VNB020167', 'Balo Lining ABSU459-1', 700000, 'Tiêu chuẩn', 2, 5, 'VNB020167.webp', 'Balo Lining ABSU459-1\n- Màu đen mạnh mẽ.\n- Chất liệu chống trầy xước, bền bỉ.', 1, 100, 0),
(147, 'VNB020166', 'Balo Lining ABSU459-2', 700000, 'Tiêu chuẩn', 2, 5, 'VNB020166.webp', 'Balo Lining ABSU459-2\n- Màu xanh dương tươi sáng.\n- Nhiều ngăn phụ tiện lợi.', 1, 100, 0),
(148, 'VNB020166_2', 'Balo Lining P-ABSV135-2', 1490000, 'Tiêu chuẩn', 2, 5, 'VNB020166.webp', 'Balo Lining P-ABSV135-2\n- Khóa kéo đôi trơn tru.\n- Quai đeo êm ái, trợ lực vai.', 1, 99, 0),
(149, 'VNB027083', 'Balo Victor BR5039 VBC', 1350000, 'Tiêu chuẩn', 3, 5, 'VNB027083.webp', 'Balo Victor BR5039 VBC\n- Bộ sưu tập CLB Cầu lông.\n- Phong cách cổ điển, hoài niệm.', 1, 100, 0),
(150, 'VNB027082', 'Balo Victor BR5042 EX/A', 1080000, 'Tiêu chuẩn', 3, 5, 'VNB027082.webp', 'Balo Victor BR5042 EX/A\n- Cảm hứng ĐH Trung Quốc.\n- Màu trắng đỏ năng động.', 1, 100, 0),
(151, 'VNB027081', 'Balo Victor BR5043 AM', 1050000, 'Tiêu chuẩn', 3, 5, 'VNB027081.webp', 'Balo Victor BR5043 AM\n- Dòng Vibrant thời trang.\n- Màu trắng xanh nhẹ nhàng.', 1, 100, 0),
(152, 'VNB027080', 'Balo Victor BR5043 AT', 1050000, 'Tiêu chuẩn', 3, 5, 'VNB027080.webp', 'Balo Victor BR5043 AT\n- Tông trắng tinh khôi.\n- Sạch sẽ, sang trọng (dễ bám bẩn).', 1, 100, 0),
(153, 'VNB026610', 'Balo Victor BR5058 HYQ', 1280000, 'Tiêu chuẩn', 3, 5, 'VNB026610.webp', 'Balo Victor BR5058 HYQ\n- Hợp tác với Huang Ya Qiong.\n- Họa tiết cỏ 4 lá may mắn.', 1, 95, 0),
(154, 'VNB020457', 'Dây Cước Yonex BG 9', 120000, '0.66mm', 1, 6, 'VNB020457.webp', 'Dây Cước Yonex BG 9\n- Cước giá rẻ, bền.\n- Tiếng nổ vang, hợp người mới chơi.', 1, 99, 0),
(155, 'VNB021511', 'Dây Cước Yonex BG EXBOLT 68', 215000, '0.68mm', 1, 6, 'VNB021511.webp', 'Dây Cước Yonex BG EXBOLT 68\n- Dòng Exbolt siêu bền.\n- Lực đẩy tốt, kiểm soát cầu ổn.', 1, 100, 0),
(156, 'VNB018745', 'Dây Cước Yonex BG 5 Match', 110000, '0.66mm', 1, 6, 'VNB018745.webp', 'Dây Cước Yonex BG 5 Match\n- Cước tập luyện kinh tế.\n- Độ bền cao, ít bị chùn dây.', 1, 100, 0),
(157, 'VNB021520', 'Dây Cước Yonex BG Aerobite Boost', 215000, 'Hỗn hợp', 1, 6, 'VNB021520.webp', 'Dây Cước Yonex BG Aerobite Boost\n- Cước lai (Hybrid).\n- Dây dọc và dây ngang khác nhau giúp tăng kiểm soát.', 1, 100, 0),
(158, 'VNB021529', 'Dây Cước Yonex BG EXBOLT 65', 200000, '0.65mm', 1, 6, 'VNB021529.webp', 'Dây Cước Yonex BG EXBOLT 65\n- Đường kính nhỏ 0.65mm.\n- Nảy cầu cực tốt, tiếng nổ to.', 1, 100, 0),
(159, 'VNB025974', 'Dây Cước Lining L64', 130000, '0.64mm', 2, 6, 'VNB025974.webp', 'Dây Cước Lining L64\n- Cước siêu mảnh 0.64mm.\n- Trợ lực tối đa cho người tay yếu.', 1, 100, 0),
(160, 'VNB021327', 'Dây Cước Lining L9', 60000, '0.75mm', 2, 6, 'VNB021327.webp', 'Dây Cước Lining L9\n- Cước siêu bền 0.75mm.\n- Khó đứt, dành cho người đánh mạnh, hay đứt cước.', 1, 100, 0),
(161, 'VNB021492', 'Dây Cước Lining N68', 150000, '0.68mm', 2, 6, 'VNB021492.webp', 'Dây Cước Lining N68\n- Đường kính 0.68mm chuẩn.\n- Cân bằng giữa độ nảy và độ bền.', 1, 99, 0),
(162, 'VNB021337', 'Dây Cước Lining N9', 60000, '0.70mm', 2, 6, 'VNB021337.webp', 'Dây Cước Lining N9\n- Cước bền bỉ, giá rẻ.\n- Giữ cân tốt, ít bị xuống cấp.', 1, 100, 0),
(163, 'VNB025975', 'Dây Cước Lining LT66', 170000, '0.66mm', 2, 6, 'VNB025975.webp', 'Dây Cước Lining LT66\n- Cước toàn diện.\n- Âm thanh hay, cảm giác cầu tốt.', 1, 100, 0),
(164, 'VNB025944', 'Dây Cước Victor VBS-70', 130000, '0.70mm', 3, 6, 'VNB025944.webp', 'Dây Cước Victor VBS-70\n- Dòng cước cao cấp bền bỉ.\n- Kiểm soát cầu chính xác.', 1, 100, 0),
(165, 'VNB025943', 'Dây Cước Victor VBS-70 Power', 125000, '0.70mm', 3, 6, 'VNB025943.webp', 'Dây Cước Victor VBS-70 Power\n- Phiên bản tăng cường sức mạnh.\n- Phủ lớp bảo vệ chống mài mòn.', 1, 87, 0),
(166, 'VNB025942', 'Dây Cước Victor VBS-69N', 160000, '0.69mm', 3, 6, 'VNB025942.webp', 'Dây Cước Victor VBS-69N\n- Chịu lực căng cao.\n- Tiếng nổ đanh, cảm giác cứng cáp.', 1, 96, 0),
(167, 'VNB025941', 'Dây Cước Victor VBS-68 Power', 160000, '0.68mm', 3, 6, 'VNB025941.webp', 'Dây Cước Victor VBS-68 Power\n- Cước mềm mại, bám cầu.\n- Hỗ trợ điều cầu hiểm hóc.', 1, 87, 0),
(168, 'VNB025940', 'Dây Cước Victor VBS-61', 5000, '0.61mm', 3, 6, 'VNB025940.webp', 'Giới thiệu sản phẩm Dây cước căng vợt Victor VBS-61\r\n- Dây cước căng vợt Victor VBS-61 là dây vợt với đường kính 0,61mm được quấn bằng sợi đa sợi siêu bền, nảy ngay lập tức khi đánh. Dây được làm từ sợi nylon siêu bền, đảm bảo độ bền. Điều này cũng giúp tăng khả năng điều khiển và tạo ra âm thanh đánh rõ nét hơn.\r\n\r\n- VBS-61 còn mang lại khả năng kiểm soát cầu tuyệt vời. Đường kính 0.61mm giúp dây bám cầu tốt hơn, cho phép người chơi điều khiển hướng đi và tốc độ của cầu một cách chính xác. Lớp phủ đặc biệt cũng góp phần tăng cường khả năng kiểm soát, giúp người chơi thực hiện các kỹ thuật khó như cắt cầu, bỏ nhỏ một cách dễ dàng và hiệu quả.\r\n- Ngoài ra, các bạn có thể tham khảo thêm những loại cước đan vợt cầu lông khác đang có tại ShopVNB để chọn được cho mình một mẫu cước đan vợt phù hợp nhé.\r\n\r\n2. Thông số Dây cước căng vợt Victor VBS-61\r\n- Chất liệu lõi: Nylon Multifilament\r\n\r\n- Lớp phủ: Đặc biệt, tăng độ bền và kiểm soát\r\n\r\n- Cảm giác: Medium Feeling\r\n\r\n- Đường kính: 0.61mm\r\n\r\n- Chiều dài: 10m\r\n\r\n- Độ bền: 7/10\r\n\r\n- Độ nảy: 9/10\r\n\r\n- Kiểm soát: 8/10\r\n\r\n- Âm thanh: 9/10\r\n\r\n3. Đối tượng phù hợp với Dây cước căng vợt Victor VBS-61\r\n- Dây cước căng vợt Victor VBS-61 cung cấp khả năng kiểm soát cầu tốt, giúp người chơi thực hiện các cú đánh chính xác và hiệu quả, mang lại cảm giác đánh êm ái, giảm thiểu rung động và chấn thương cho cổ tay và khuỷu tay.', 1, 84, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hoten` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `role` tinyint(4) DEFAULT 1,
  `avatar` varchar(255) DEFAULT NULL,
  `diem_tich_luy` int(11) DEFAULT 0,
  `diem_thanh_vien` int(11) DEFAULT 0,
  `ngay_quay_free` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `username`, `password`, `hoten`, `email`, `dia_chi`, `so_dien_thoai`, `role`, `avatar`, `diem_tich_luy`, `diem_thanh_vien`, `ngay_quay_free`) VALUES
(1, 'admin', '123456', 'Quản Trị Viên', 'admin@evashop.com', NULL, NULL, 1, NULL, 0, 0, NULL),
(2, 'hqa', '123456', 'Huỳnh Quốc Vinh', 'vinh26132005@gmail.com', 'h246/85 chánh nghĩa bình dương', '0344138743', 0, 'uploads/1765934494_wp14367291-gengar-pc-wallpapers.png', 19500, 400, '2025-12-30'),
(3, 'vinh2613', '123456', 'Huỳnh Quốc Vinh', 'vinh26132005@gmail.com', '123', '0344138743', 0, NULL, 100, 2200, '2025-12-30'),
(4, 'khangvi', '123456', 'Trần Khang Vĩ', 'trankhangvitpk@gmail.com', NULL, NULL, 0, NULL, 0, 100, '2025-12-21'),
(5, 'tenvaho', '123456', 'Tên Và Họ ', 'vinh26132005@gmail.com', '123', '0344138743', 0, NULL, 100, 100, '2025-12-29'),
(6, 'qv', '123456', 'Huỳnh Quốc Vinh', 'vinh26132005@gmail.com', NULL, NULL, 0, NULL, 0, 0, NULL),
(7, 'nam', '123456', 'nnnn', 'vinh26132005@gmail.com', NULL, NULL, 0, NULL, 0, 0, NULL),
(8, 'nam123', '123456', 'mmm hhh', 'vinh26132005@gmail.com', NULL, NULL, 0, NULL, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuonghieu`
--

CREATE TABLE `thuonghieu` (
  `id` int(11) NOT NULL,
  `ten_thuonghieu` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuonghieu`
--

INSERT INTO `thuonghieu` (`id`, `ten_thuonghieu`, `hinh_anh`) VALUES
(1, 'Yonex', 'logo-yonex.jpg'),
(2, 'Lining', 'logo-lining.jpg'),
(3, 'Victor', 'logo-victor.jpg'),
(4, 'Mizuno', 'logo-mizuno.jpg'),
(5, 'Kawasaki', 'logo-kawasaki.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuvienanh`
--

CREATE TABLE `thuvienanh` (
  `id` int(11) NOT NULL,
  `sanpham_id` int(11) DEFAULT NULL,
  `ten_file_anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuvienanh`
--

INSERT INTO `thuvienanh` (`id`, `sanpham_id`, `ten_file_anh`) VALUES
(1, 133, 'VNB015140_1.webp'),
(2, 95, 'VNB015778_1.webp'),
(3, 94, 'VNB015779_1.webp'),
(4, 93, 'VNB016823_1.webp'),
(5, 156, 'VNB018745_1.webp'),
(6, 134, 'VNB019196_1.webp'),
(7, 147, 'VNB020166_1.webp'),
(8, 148, 'VNB020166_2_1.webp'),
(9, 146, 'VNB020167_1.webp'),
(10, 137, 'VNB020201_1.webp'),
(11, 154, 'VNB020457_1.webp'),
(12, 33, 'VNB020543_1.webp'),
(13, 34, 'VNB020685_1.webp'),
(14, 80, 'VNB021291_1.webp'),
(15, 160, 'VNB021327_1.webp'),
(16, 162, 'VNB021337_1.webp'),
(17, 161, 'VNB021492_1.webp'),
(18, 155, 'VNB021511_1.webp'),
(19, 157, 'VNB021520_1.webp'),
(20, 158, 'VNB021529_1.webp'),
(21, 20, 'VNB021973_1.webp'),
(22, 36, 'VNB021995_1.webp'),
(23, 35, 'VNB021999_1.webp'),
(24, 75, 'VNB022304_1.webp'),
(25, 74, 'VNB022305_1.webp'),
(26, 19, 'VNB022968_1.webp'),
(27, 18, 'VNB022970_1.webp'),
(28, 17, 'VNB022972_1.webp'),
(29, 16, 'VNB022973_1.webp'),
(30, 15, 'VNB022977_1.webp'),
(31, 14, 'VNB022980_1.webp'),
(32, 92, 'VNB023095_1.webp'),
(33, 91, 'VNB023096_1.webp'),
(34, 90, 'VNB023128_1.webp'),
(35, 89, 'VNB023134_1.webp'),
(36, 88, 'VNB023146_1.webp'),
(37, 87, 'VNB023148_1.webp'),
(38, 86, 'VNB023162_1.webp'),
(39, 85, 'VNB023163_1.webp'),
(40, 84, 'VNB023165_1.webp'),
(41, 83, 'VNB023176_1.webp'),
(42, 82, 'VNB023193_1.webp'),
(43, 81, 'VNB023207_1.webp'),
(44, 79, 'VNB023646_1.webp'),
(45, 32, 'VNB023702_1.webp'),
(46, 31, 'VNB023703_1.webp'),
(47, 30, 'VNB023704_1.webp'),
(48, 73, 'VNB023980_1.webp'),
(49, 72, 'VNB023981_1.webp'),
(50, 13, 'VNB024012_1.webp'),
(51, 55, 'VNB024052_1.webp'),
(52, 12, 'VNB024192_1.webp'),
(53, 3, 'VNB024193_1.webp'),
(54, 2, 'VNB024194_1.webp'),
(55, 29, 'VNB024441_1.webp'),
(56, 24, 'VNB024442_1.webp'),
(57, 54, 'VNB024722_1.webp'),
(58, 23, 'VNB024848_1.webp'),
(59, 28, 'VNB024856_1.webp'),
(60, 22, 'VNB024860_1.webp'),
(61, 53, 'VNB024937_1.webp'),
(62, 52, 'VNB024938_1.webp'),
(63, 27, 'VNB024963_1.webp'),
(64, 71, 'VNB025037_1.webp'),
(65, 70, 'VNB025045_1.webp'),
(66, 69, 'VNB025049_1.webp'),
(67, 68, 'VNB025053_1.webp'),
(68, 103, 'VNB025073_1.webp'),
(69, 78, 'VNB025130_1.webp'),
(70, 51, 'VNB025142_1.webp'),
(71, 50, 'VNB025143_1.webp'),
(72, 49, 'VNB025347_1.webp'),
(73, 48, 'VNB025374_1.webp'),
(74, 118, 'VNB025422_1.webp'),
(75, 117, 'VNB025423_1.webp'),
(76, 116, 'VNB025424_1.webp'),
(77, 115, 'VNB025426_1.webp'),
(78, 47, 'VNB025429_1.webp'),
(79, 123, 'VNB025475_1.webp'),
(80, 122, 'VNB025476_1.webp'),
(81, 121, 'VNB025477_1.webp'),
(82, 120, 'VNB025478_1.webp'),
(83, 119, 'VNB025517_1.webp'),
(84, 21, 'VNB025565_1.webp'),
(85, 46, 'VNB025622_1.webp'),
(86, 45, 'VNB025623_1.webp'),
(87, 132, 'VNB025639_1.webp'),
(88, 131, 'VNB025640_1.webp'),
(89, 130, 'VNB025641_1.webp'),
(90, 136, 'VNB025654_1.webp'),
(91, 135, 'VNB025687_1.webp'),
(92, 138, 'VNB025689_1.webp'),
(93, 67, 'VNB025841_1.webp'),
(94, 66, 'VNB025842_1.webp'),
(95, 59, 'VNB025843_1.webp'),
(96, 58, 'VNB025844_1.webp'),
(97, 57, 'VNB025845_1.webp'),
(98, 65, 'VNB025846_1.webp'),
(99, 44, 'VNB025847_1.webp'),
(100, 39, 'VNB025848_1.webp'),
(101, 43, 'VNB025849_1.webp'),
(102, 143, 'VNB025898_1.webp'),
(103, 142, 'VNB025903_1.webp'),
(104, 141, 'VNB025908_1.webp'),
(105, 64, 'VNB025934_1.webp'),
(106, 168, 'VNB025940_1.webp'),
(107, 167, 'VNB025941_1.webp'),
(108, 166, 'VNB025942_1.webp'),
(109, 165, 'VNB025943_1.webp'),
(110, 164, 'VNB025944_1.webp'),
(111, 77, 'VNB025971_1.webp'),
(112, 76, 'VNB025972_1.webp'),
(113, 159, 'VNB025974_1.webp'),
(114, 163, 'VNB025975_1.webp'),
(115, 140, 'VNB025983_1.webp'),
(116, 63, 'VNB026064_1.webp'),
(117, 62, 'VNB026065_1.webp'),
(118, 124, 'VNB026097_1.webp'),
(119, 42, 'VNB026132_1.webp'),
(120, 61, 'VNB026281_1.webp'),
(121, 56, 'VNB026282_1.webp'),
(122, 38, 'VNB026283_1.webp'),
(123, 60, 'VNB026291_1.webp'),
(124, 11, 'VNB026410_1.webp'),
(125, 10, 'VNB026411_1.webp'),
(126, 7, 'VNB026413_1.webp'),
(127, 9, 'VNB026413_P_1.webp'),
(128, 145, 'VNB026441_1.webp'),
(129, 100, 'VNB026444_1.webp'),
(130, 144, 'VNB026447_1.webp'),
(131, 153, 'VNB026610_1.webp'),
(132, 8, 'VNB026653_1.webp'),
(133, 6, 'VNB026668_1.webp'),
(134, 1, 'VNB026679_1.webp'),
(135, 99, 'VNB026740_1.webp'),
(136, 102, 'VNB026761_1.webp'),
(137, 139, 'VNB026763_1.webp'),
(138, 26, 'VNB026781_1.webp'),
(139, 25, 'VNB026782_1.webp'),
(140, 101, 'VNB026794_1.webp'),
(141, 128, 'VNB026883_1.webp'),
(142, 127, 'VNB026884_1.webp'),
(143, 104, 'VNB026956_1.webp'),
(144, 41, 'VNB026962_1.webp'),
(145, 108, 'VNB026968_1.webp'),
(146, 107, 'VNB026969_1.webp'),
(147, 106, 'VNB026970_1.webp'),
(148, 98, 'VNB026977_1.webp'),
(149, 5, 'VNB026983_1.webp'),
(150, 97, 'VNB026984_1.webp'),
(151, 4, 'VNB026987_1.webp'),
(152, 37, 'VNB026991_1.webp'),
(153, 40, 'VNB026992_1.webp'),
(154, 96, 'VNB027031_1.webp'),
(155, 152, 'VNB027080_1.webp'),
(156, 151, 'VNB027081_1.webp'),
(157, 150, 'VNB027082_1.webp'),
(158, 149, 'VNB027083_1.webp'),
(159, 113, 'VNB027169_1.webp'),
(160, 126, 'VNB027171_1.webp'),
(161, 125, 'VNB027172_1.webp'),
(162, 105, 'VNB027179_1.webp'),
(163, 109, 'VNB027180_1.webp'),
(164, 129, 'VNB027229_1.webp'),
(165, 114, 'VNB027232_1.webp'),
(166, 112, 'VNB027265_1.webp'),
(167, 111, 'VNB027269_1.webp'),
(168, 110, 'VNB027271_1.webp'),
(256, 133, 'VNB015140_2.webp'),
(257, 95, 'VNB015778_2.webp'),
(258, 94, 'VNB015779_2.webp'),
(259, 93, 'VNB016823_2.webp'),
(260, 156, 'VNB018745_2.webp'),
(261, 134, 'VNB019196_2.webp'),
(262, 147, 'VNB020166_2.webp'),
(263, 148, 'VNB020166_2_2.webp'),
(264, 146, 'VNB020167_2.webp'),
(265, 137, 'VNB020201_2.webp'),
(266, 154, 'VNB020457_2.webp'),
(267, 33, 'VNB020543_2.webp'),
(268, 34, 'VNB020685_2.webp'),
(269, 80, 'VNB021291_2.webp'),
(270, 160, 'VNB021327_2.webp'),
(271, 162, 'VNB021337_2.webp'),
(272, 161, 'VNB021492_2.webp'),
(273, 155, 'VNB021511_2.webp'),
(274, 157, 'VNB021520_2.webp'),
(275, 158, 'VNB021529_2.webp'),
(276, 20, 'VNB021973_2.webp'),
(277, 36, 'VNB021995_2.webp'),
(278, 35, 'VNB021999_2.webp'),
(279, 75, 'VNB022304_2.webp'),
(280, 74, 'VNB022305_2.webp'),
(281, 19, 'VNB022968_2.webp'),
(282, 18, 'VNB022970_2.webp'),
(283, 17, 'VNB022972_2.webp'),
(284, 16, 'VNB022973_2.webp'),
(285, 15, 'VNB022977_2.webp'),
(286, 14, 'VNB022980_2.webp'),
(287, 92, 'VNB023095_2.webp'),
(288, 91, 'VNB023096_2.webp'),
(289, 90, 'VNB023128_2.webp'),
(290, 89, 'VNB023134_2.webp'),
(291, 88, 'VNB023146_2.webp'),
(292, 87, 'VNB023148_2.webp'),
(293, 86, 'VNB023162_2.webp'),
(294, 85, 'VNB023163_2.webp'),
(295, 84, 'VNB023165_2.webp'),
(296, 83, 'VNB023176_2.webp'),
(297, 82, 'VNB023193_2.webp'),
(298, 81, 'VNB023207_2.webp'),
(299, 79, 'VNB023646_2.webp'),
(300, 32, 'VNB023702_2.webp'),
(301, 31, 'VNB023703_2.webp'),
(302, 30, 'VNB023704_2.webp'),
(303, 73, 'VNB023980_2.webp'),
(304, 72, 'VNB023981_2.webp'),
(305, 13, 'VNB024012_2.webp'),
(306, 55, 'VNB024052_2.webp'),
(307, 12, 'VNB024192_2.webp'),
(308, 3, 'VNB024193_2.webp'),
(309, 2, 'VNB024194_2.webp'),
(310, 29, 'VNB024441_2.webp'),
(311, 24, 'VNB024442_2.webp'),
(312, 54, 'VNB024722_2.webp'),
(313, 23, 'VNB024848_2.webp'),
(314, 28, 'VNB024856_2.webp'),
(315, 22, 'VNB024860_2.webp'),
(316, 53, 'VNB024937_2.webp'),
(317, 52, 'VNB024938_2.webp'),
(318, 27, 'VNB024963_2.webp'),
(319, 71, 'VNB025037_2.webp'),
(320, 70, 'VNB025045_2.webp'),
(321, 69, 'VNB025049_2.webp'),
(322, 68, 'VNB025053_2.webp'),
(323, 103, 'VNB025073_2.webp'),
(324, 78, 'VNB025130_2.webp'),
(325, 51, 'VNB025142_2.webp'),
(326, 50, 'VNB025143_2.webp'),
(327, 49, 'VNB025347_2.webp'),
(328, 48, 'VNB025374_2.webp'),
(329, 118, 'VNB025422_2.webp'),
(330, 117, 'VNB025423_2.webp'),
(331, 116, 'VNB025424_2.webp'),
(332, 115, 'VNB025426_2.webp'),
(333, 47, 'VNB025429_2.webp'),
(334, 123, 'VNB025475_2.webp'),
(335, 122, 'VNB025476_2.webp'),
(336, 121, 'VNB025477_2.webp'),
(337, 120, 'VNB025478_2.webp'),
(338, 119, 'VNB025517_2.webp'),
(339, 21, 'VNB025565_2.webp'),
(340, 46, 'VNB025622_2.webp'),
(341, 45, 'VNB025623_2.webp'),
(342, 132, 'VNB025639_2.webp'),
(343, 131, 'VNB025640_2.webp'),
(344, 130, 'VNB025641_2.webp'),
(345, 136, 'VNB025654_2.webp'),
(346, 135, 'VNB025687_2.webp'),
(347, 138, 'VNB025689_2.webp'),
(348, 67, 'VNB025841_2.webp'),
(349, 66, 'VNB025842_2.webp'),
(350, 59, 'VNB025843_2.webp'),
(351, 58, 'VNB025844_2.webp'),
(352, 57, 'VNB025845_2.webp'),
(353, 65, 'VNB025846_2.webp'),
(354, 44, 'VNB025847_2.webp'),
(355, 39, 'VNB025848_2.webp'),
(356, 43, 'VNB025849_2.webp'),
(357, 143, 'VNB025898_2.webp'),
(358, 142, 'VNB025903_2.webp'),
(359, 141, 'VNB025908_2.webp'),
(360, 64, 'VNB025934_2.webp'),
(361, 168, 'VNB025940_2.webp'),
(362, 167, 'VNB025941_2.webp'),
(363, 166, 'VNB025942_2.webp'),
(364, 165, 'VNB025943_2.webp'),
(365, 164, 'VNB025944_2.webp'),
(366, 77, 'VNB025971_2.webp'),
(367, 76, 'VNB025972_2.webp'),
(368, 159, 'VNB025974_2.webp'),
(369, 163, 'VNB025975_2.webp'),
(370, 140, 'VNB025983_2.webp'),
(371, 63, 'VNB026064_2.webp'),
(372, 62, 'VNB026065_2.webp'),
(373, 124, 'VNB026097_2.webp'),
(374, 42, 'VNB026132_2.webp'),
(375, 61, 'VNB026281_2.webp'),
(376, 56, 'VNB026282_2.webp'),
(377, 38, 'VNB026283_2.webp'),
(378, 60, 'VNB026291_2.webp'),
(379, 11, 'VNB026410_2.webp'),
(380, 10, 'VNB026411_2.webp'),
(381, 7, 'VNB026413_2.webp'),
(382, 9, 'VNB026413_P_2.webp'),
(383, 145, 'VNB026441_2.webp'),
(384, 100, 'VNB026444_2.webp'),
(385, 144, 'VNB026447_2.webp'),
(386, 153, 'VNB026610_2.webp'),
(387, 8, 'VNB026653_2.webp'),
(388, 6, 'VNB026668_2.webp'),
(389, 1, 'VNB026679_2.webp'),
(390, 99, 'VNB026740_2.webp'),
(391, 102, 'VNB026761_2.webp'),
(392, 139, 'VNB026763_2.webp'),
(393, 26, 'VNB026781_2.webp'),
(394, 25, 'VNB026782_2.webp'),
(395, 101, 'VNB026794_2.webp'),
(396, 128, 'VNB026883_2.webp'),
(397, 127, 'VNB026884_2.webp'),
(398, 104, 'VNB026956_2.webp'),
(399, 41, 'VNB026962_2.webp'),
(400, 108, 'VNB026968_2.webp'),
(401, 107, 'VNB026969_2.webp'),
(402, 106, 'VNB026970_2.webp'),
(403, 98, 'VNB026977_2.webp'),
(404, 5, 'VNB026983_2.webp'),
(405, 97, 'VNB026984_2.webp'),
(406, 4, 'VNB026987_2.webp'),
(407, 37, 'VNB026991_2.webp'),
(408, 40, 'VNB026992_2.webp'),
(409, 96, 'VNB027031_2.webp'),
(410, 152, 'VNB027080_2.webp'),
(411, 151, 'VNB027081_2.webp'),
(412, 150, 'VNB027082_2.webp'),
(413, 149, 'VNB027083_2.webp'),
(414, 113, 'VNB027169_2.webp'),
(415, 126, 'VNB027171_2.webp'),
(416, 125, 'VNB027172_2.webp'),
(417, 105, 'VNB027179_2.webp'),
(418, 109, 'VNB027180_2.webp'),
(419, 129, 'VNB027229_2.webp'),
(420, 114, 'VNB027232_2.webp'),
(421, 112, 'VNB027265_2.webp'),
(422, 111, 'VNB027269_2.webp'),
(423, 110, 'VNB027271_2.webp'),
(511, 133, 'VNB015140_3.webp'),
(512, 95, 'VNB015778_3.webp'),
(513, 94, 'VNB015779_3.webp'),
(514, 93, 'VNB016823_3.webp'),
(515, 156, 'VNB018745_3.webp'),
(516, 134, 'VNB019196_3.webp'),
(517, 147, 'VNB020166_3.webp'),
(518, 148, 'VNB020166_2_3.webp'),
(519, 146, 'VNB020167_3.webp'),
(520, 137, 'VNB020201_3.webp'),
(521, 154, 'VNB020457_3.webp'),
(522, 33, 'VNB020543_3.webp'),
(523, 34, 'VNB020685_3.webp'),
(524, 80, 'VNB021291_3.webp'),
(525, 160, 'VNB021327_3.webp'),
(526, 162, 'VNB021337_3.webp'),
(527, 161, 'VNB021492_3.webp'),
(528, 155, 'VNB021511_3.webp'),
(529, 157, 'VNB021520_3.webp'),
(530, 158, 'VNB021529_3.webp'),
(531, 20, 'VNB021973_3.webp'),
(532, 36, 'VNB021995_3.webp'),
(533, 35, 'VNB021999_3.webp'),
(534, 75, 'VNB022304_3.webp'),
(535, 74, 'VNB022305_3.webp'),
(536, 19, 'VNB022968_3.webp'),
(537, 18, 'VNB022970_3.webp'),
(538, 17, 'VNB022972_3.webp'),
(539, 16, 'VNB022973_3.webp'),
(540, 15, 'VNB022977_3.webp'),
(541, 14, 'VNB022980_3.webp'),
(542, 92, 'VNB023095_3.webp'),
(543, 91, 'VNB023096_3.webp'),
(544, 90, 'VNB023128_3.webp'),
(545, 89, 'VNB023134_3.webp'),
(546, 88, 'VNB023146_3.webp'),
(547, 87, 'VNB023148_3.webp'),
(548, 86, 'VNB023162_3.webp'),
(549, 85, 'VNB023163_3.webp'),
(550, 84, 'VNB023165_3.webp'),
(551, 83, 'VNB023176_3.webp'),
(552, 82, 'VNB023193_3.webp'),
(553, 81, 'VNB023207_3.webp'),
(554, 79, 'VNB023646_3.webp'),
(555, 32, 'VNB023702_3.webp'),
(556, 31, 'VNB023703_3.webp'),
(557, 30, 'VNB023704_3.webp'),
(558, 73, 'VNB023980_3.webp'),
(559, 72, 'VNB023981_3.webp'),
(560, 13, 'VNB024012_3.webp'),
(561, 55, 'VNB024052_3.webp'),
(562, 12, 'VNB024192_3.webp'),
(563, 3, 'VNB024193_3.webp'),
(564, 2, 'VNB024194_3.webp'),
(565, 29, 'VNB024441_3.webp'),
(566, 24, 'VNB024442_3.webp'),
(567, 54, 'VNB024722_3.webp'),
(568, 23, 'VNB024848_3.webp'),
(569, 28, 'VNB024856_3.webp'),
(570, 22, 'VNB024860_3.webp'),
(571, 53, 'VNB024937_3.webp'),
(572, 52, 'VNB024938_3.webp'),
(573, 27, 'VNB024963_3.webp'),
(574, 71, 'VNB025037_3.webp'),
(575, 70, 'VNB025045_3.webp'),
(576, 69, 'VNB025049_3.webp'),
(577, 68, 'VNB025053_3.webp'),
(578, 103, 'VNB025073_3.webp'),
(579, 78, 'VNB025130_3.webp'),
(580, 51, 'VNB025142_3.webp'),
(581, 50, 'VNB025143_3.webp'),
(582, 49, 'VNB025347_3.webp'),
(583, 48, 'VNB025374_3.webp'),
(584, 118, 'VNB025422_3.webp'),
(585, 117, 'VNB025423_3.webp'),
(586, 116, 'VNB025424_3.webp'),
(587, 115, 'VNB025426_3.webp'),
(588, 47, 'VNB025429_3.webp'),
(589, 123, 'VNB025475_3.webp'),
(590, 122, 'VNB025476_3.webp'),
(591, 121, 'VNB025477_3.webp'),
(592, 120, 'VNB025478_3.webp'),
(593, 119, 'VNB025517_3.webp'),
(594, 21, 'VNB025565_3.webp'),
(595, 46, 'VNB025622_3.webp'),
(596, 45, 'VNB025623_3.webp'),
(597, 132, 'VNB025639_3.webp'),
(598, 131, 'VNB025640_3.webp'),
(599, 130, 'VNB025641_3.webp'),
(600, 136, 'VNB025654_3.webp'),
(601, 135, 'VNB025687_3.webp'),
(602, 138, 'VNB025689_3.webp'),
(603, 67, 'VNB025841_3.webp'),
(604, 66, 'VNB025842_3.webp'),
(605, 59, 'VNB025843_3.webp'),
(606, 58, 'VNB025844_3.webp'),
(607, 57, 'VNB025845_3.webp'),
(608, 65, 'VNB025846_3.webp'),
(609, 44, 'VNB025847_3.webp'),
(610, 39, 'VNB025848_3.webp'),
(611, 43, 'VNB025849_3.webp'),
(612, 143, 'VNB025898_3.webp'),
(613, 142, 'VNB025903_3.webp'),
(614, 141, 'VNB025908_3.webp'),
(615, 64, 'VNB025934_3.webp'),
(616, 168, 'VNB025940_3.webp'),
(617, 167, 'VNB025941_3.webp'),
(618, 166, 'VNB025942_3.webp'),
(619, 165, 'VNB025943_3.webp'),
(620, 164, 'VNB025944_3.webp'),
(621, 77, 'VNB025971_3.webp'),
(622, 76, 'VNB025972_3.webp'),
(623, 159, 'VNB025974_3.webp'),
(624, 163, 'VNB025975_3.webp'),
(625, 140, 'VNB025983_3.webp'),
(626, 63, 'VNB026064_3.webp'),
(627, 62, 'VNB026065_3.webp'),
(628, 124, 'VNB026097_3.webp'),
(629, 42, 'VNB026132_3.webp'),
(630, 61, 'VNB026281_3.webp'),
(631, 56, 'VNB026282_3.webp'),
(632, 38, 'VNB026283_3.webp'),
(633, 60, 'VNB026291_3.webp'),
(634, 11, 'VNB026410_3.webp'),
(635, 10, 'VNB026411_3.webp'),
(636, 7, 'VNB026413_3.webp'),
(637, 9, 'VNB026413_P_3.webp'),
(638, 145, 'VNB026441_3.webp'),
(639, 100, 'VNB026444_3.webp'),
(640, 144, 'VNB026447_3.webp'),
(641, 153, 'VNB026610_3.webp'),
(642, 8, 'VNB026653_3.webp'),
(643, 6, 'VNB026668_3.webp'),
(644, 1, 'VNB026679_3.webp'),
(645, 99, 'VNB026740_3.webp'),
(646, 102, 'VNB026761_3.webp'),
(647, 139, 'VNB026763_3.webp'),
(648, 26, 'VNB026781_3.webp'),
(649, 25, 'VNB026782_3.webp'),
(650, 101, 'VNB026794_3.webp'),
(651, 128, 'VNB026883_3.webp'),
(652, 127, 'VNB026884_3.webp'),
(653, 104, 'VNB026956_3.webp'),
(654, 41, 'VNB026962_3.webp'),
(655, 108, 'VNB026968_3.webp'),
(656, 107, 'VNB026969_3.webp'),
(657, 106, 'VNB026970_3.webp'),
(658, 98, 'VNB026977_3.webp'),
(659, 5, 'VNB026983_3.webp'),
(660, 97, 'VNB026984_3.webp'),
(661, 4, 'VNB026987_3.webp'),
(662, 37, 'VNB026991_3.webp'),
(663, 40, 'VNB026992_3.webp'),
(664, 96, 'VNB027031_3.webp'),
(665, 152, 'VNB027080_3.webp'),
(666, 151, 'VNB027081_3.webp'),
(667, 150, 'VNB027082_3.webp'),
(668, 149, 'VNB027083_3.webp'),
(669, 113, 'VNB027169_3.webp'),
(670, 126, 'VNB027171_3.webp'),
(671, 125, 'VNB027172_3.webp'),
(672, 105, 'VNB027179_3.webp'),
(673, 109, 'VNB027180_3.webp'),
(674, 129, 'VNB027229_3.webp'),
(675, 114, 'VNB027232_3.webp'),
(676, 112, 'VNB027265_3.webp'),
(677, 111, 'VNB027269_3.webp'),
(678, 110, 'VNB027271_3.webp'),
(766, 133, 'VNB015140_4.webp'),
(767, 95, 'VNB015778_4.webp'),
(768, 94, 'VNB015779_4.webp'),
(769, 93, 'VNB016823_4.webp'),
(770, 156, 'VNB018745_4.webp'),
(771, 134, 'VNB019196_4.webp'),
(772, 147, 'VNB020166_4.webp'),
(773, 148, 'VNB020166_2_4.webp'),
(774, 146, 'VNB020167_4.webp'),
(775, 137, 'VNB020201_4.webp'),
(776, 154, 'VNB020457_4.webp'),
(777, 33, 'VNB020543_4.webp'),
(778, 34, 'VNB020685_4.webp'),
(779, 80, 'VNB021291_4.webp'),
(780, 160, 'VNB021327_4.webp'),
(781, 162, 'VNB021337_4.webp'),
(782, 161, 'VNB021492_4.webp'),
(783, 155, 'VNB021511_4.webp'),
(784, 157, 'VNB021520_4.webp'),
(785, 158, 'VNB021529_4.webp'),
(786, 20, 'VNB021973_4.webp'),
(787, 36, 'VNB021995_4.webp'),
(788, 35, 'VNB021999_4.webp'),
(789, 75, 'VNB022304_4.webp'),
(790, 74, 'VNB022305_4.webp'),
(791, 19, 'VNB022968_4.webp'),
(792, 18, 'VNB022970_4.webp'),
(793, 17, 'VNB022972_4.webp'),
(794, 16, 'VNB022973_4.webp'),
(795, 15, 'VNB022977_4.webp'),
(796, 14, 'VNB022980_4.webp'),
(797, 92, 'VNB023095_4.webp'),
(798, 91, 'VNB023096_4.webp'),
(799, 90, 'VNB023128_4.webp'),
(800, 89, 'VNB023134_4.webp'),
(801, 88, 'VNB023146_4.webp'),
(802, 87, 'VNB023148_4.webp'),
(803, 86, 'VNB023162_4.webp'),
(804, 85, 'VNB023163_4.webp'),
(805, 84, 'VNB023165_4.webp'),
(806, 83, 'VNB023176_4.webp'),
(807, 82, 'VNB023193_4.webp'),
(808, 81, 'VNB023207_4.webp'),
(809, 79, 'VNB023646_4.webp'),
(810, 32, 'VNB023702_4.webp'),
(811, 31, 'VNB023703_4.webp'),
(812, 30, 'VNB023704_4.webp'),
(813, 73, 'VNB023980_4.webp'),
(814, 72, 'VNB023981_4.webp'),
(815, 13, 'VNB024012_4.webp'),
(816, 55, 'VNB024052_4.webp'),
(817, 12, 'VNB024192_4.webp'),
(818, 3, 'VNB024193_4.webp'),
(819, 2, 'VNB024194_4.webp'),
(820, 29, 'VNB024441_4.webp'),
(821, 24, 'VNB024442_4.webp'),
(822, 54, 'VNB024722_4.webp'),
(823, 23, 'VNB024848_4.webp'),
(824, 28, 'VNB024856_4.webp'),
(825, 22, 'VNB024860_4.webp'),
(826, 53, 'VNB024937_4.webp'),
(827, 52, 'VNB024938_4.webp'),
(828, 27, 'VNB024963_4.webp'),
(829, 71, 'VNB025037_4.webp'),
(830, 70, 'VNB025045_4.webp'),
(831, 69, 'VNB025049_4.webp'),
(832, 68, 'VNB025053_4.webp'),
(833, 103, 'VNB025073_4.webp'),
(834, 78, 'VNB025130_4.webp'),
(835, 51, 'VNB025142_4.webp'),
(836, 50, 'VNB025143_4.webp'),
(837, 49, 'VNB025347_4.webp'),
(838, 48, 'VNB025374_4.webp'),
(839, 118, 'VNB025422_4.webp'),
(840, 117, 'VNB025423_4.webp'),
(841, 116, 'VNB025424_4.webp'),
(842, 115, 'VNB025426_4.webp'),
(843, 47, 'VNB025429_4.webp'),
(844, 123, 'VNB025475_4.webp'),
(845, 122, 'VNB025476_4.webp'),
(846, 121, 'VNB025477_4.webp'),
(847, 120, 'VNB025478_4.webp'),
(848, 119, 'VNB025517_4.webp'),
(849, 21, 'VNB025565_4.webp'),
(850, 46, 'VNB025622_4.webp'),
(851, 45, 'VNB025623_4.webp'),
(852, 132, 'VNB025639_4.webp'),
(853, 131, 'VNB025640_4.webp'),
(854, 130, 'VNB025641_4.webp'),
(855, 136, 'VNB025654_4.webp'),
(856, 135, 'VNB025687_4.webp'),
(857, 138, 'VNB025689_4.webp'),
(858, 67, 'VNB025841_4.webp'),
(859, 66, 'VNB025842_4.webp'),
(860, 59, 'VNB025843_4.webp'),
(861, 58, 'VNB025844_4.webp'),
(862, 57, 'VNB025845_4.webp'),
(863, 65, 'VNB025846_4.webp'),
(864, 44, 'VNB025847_4.webp'),
(865, 39, 'VNB025848_4.webp'),
(866, 43, 'VNB025849_4.webp'),
(867, 143, 'VNB025898_4.webp'),
(868, 142, 'VNB025903_4.webp'),
(869, 141, 'VNB025908_4.webp'),
(870, 64, 'VNB025934_4.webp'),
(871, 168, 'VNB025940_4.webp'),
(872, 167, 'VNB025941_4.webp'),
(873, 166, 'VNB025942_4.webp'),
(874, 165, 'VNB025943_4.webp'),
(875, 164, 'VNB025944_4.webp'),
(876, 77, 'VNB025971_4.webp'),
(877, 76, 'VNB025972_4.webp'),
(878, 159, 'VNB025974_4.webp'),
(879, 163, 'VNB025975_4.webp'),
(880, 140, 'VNB025983_4.webp'),
(881, 63, 'VNB026064_4.webp'),
(882, 62, 'VNB026065_4.webp'),
(883, 124, 'VNB026097_4.webp'),
(884, 42, 'VNB026132_4.webp'),
(885, 61, 'VNB026281_4.webp'),
(886, 56, 'VNB026282_4.webp'),
(887, 38, 'VNB026283_4.webp'),
(888, 60, 'VNB026291_4.webp'),
(889, 11, 'VNB026410_4.webp'),
(890, 10, 'VNB026411_4.webp'),
(891, 7, 'VNB026413_4.webp'),
(892, 9, 'VNB026413_P_4.webp'),
(893, 145, 'VNB026441_4.webp'),
(894, 100, 'VNB026444_4.webp'),
(895, 144, 'VNB026447_4.webp'),
(896, 153, 'VNB026610_4.webp'),
(897, 8, 'VNB026653_4.webp'),
(898, 6, 'VNB026668_4.webp'),
(899, 1, 'VNB026679_4.webp'),
(900, 99, 'VNB026740_4.webp'),
(901, 102, 'VNB026761_4.webp'),
(902, 139, 'VNB026763_4.webp'),
(903, 26, 'VNB026781_4.webp'),
(904, 25, 'VNB026782_4.webp'),
(905, 101, 'VNB026794_4.webp'),
(906, 128, 'VNB026883_4.webp'),
(907, 127, 'VNB026884_4.webp'),
(908, 104, 'VNB026956_4.webp'),
(909, 41, 'VNB026962_4.webp'),
(910, 108, 'VNB026968_4.webp'),
(911, 107, 'VNB026969_4.webp'),
(912, 106, 'VNB026970_4.webp'),
(913, 98, 'VNB026977_4.webp'),
(914, 5, 'VNB026983_4.webp'),
(915, 97, 'VNB026984_4.webp'),
(916, 4, 'VNB026987_4.webp'),
(917, 37, 'VNB026991_4.webp'),
(918, 40, 'VNB026992_4.webp'),
(919, 96, 'VNB027031_4.webp'),
(920, 152, 'VNB027080_4.webp'),
(921, 151, 'VNB027081_4.webp'),
(922, 150, 'VNB027082_4.webp'),
(923, 149, 'VNB027083_4.webp'),
(924, 113, 'VNB027169_4.webp'),
(925, 126, 'VNB027171_4.webp'),
(926, 125, 'VNB027172_4.webp'),
(927, 105, 'VNB027179_4.webp'),
(928, 109, 'VNB027180_4.webp'),
(929, 129, 'VNB027229_4.webp'),
(930, 114, 'VNB027232_4.webp'),
(931, 112, 'VNB027265_4.webp'),
(932, 111, 'VNB027269_4.webp'),
(933, 110, 'VNB027271_4.webp');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuc`
--

CREATE TABLE `tintuc` (
  `id` int(11) NOT NULL,
  `tieu_de` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `noi_dung_tom_tat` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `noi_dung_chi_tiet` longtext DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `ngay_dang` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tintuc`
--

INSERT INTO `tintuc` (`id`, `tieu_de`, `noi_dung_tom_tat`, `noi_dung_chi_tiet`, `hinh_anh`, `ngay_dang`) VALUES
(1, 'Khám phá sân cầu lông Ecosport Gò Dầu chất lượng và uy tín tại Tân Phú', 'Sau thành công tại Gò Vấp, Ecosport tiếp tục mang đến trải nghiệm cầu lông chất lượng tại Tân Phú...', '<p><strong>1. Giới thiệu về sân cầu lông Ecosport Gò Dầu</strong><br>Sân cầu lông Ecosport Gò Dầu sở hữu tổng cộng 8 sân chơi tiêu chuẩn, bao gồm một sân private dành cho những ai muốn trải nghiệm không gian riêng tư, yên tĩnh.</p>\r\n   <p><strong>2. Giá thuê sân</strong><br>Giá thuê sân cầu lông Ecosport Gò Dầu linh hoạt, dao động từ 60.000 – 120.000đ/giờ, tùy theo khung giờ.</p>\r\n   <p><strong>3. Các dịch vụ tiện ích</strong><br>- Bãi giữ xe rộng rãi<br>- Quầy căn tin<br>- Nhà vệ sinh sạch sẽ<br>- Hệ thống đèn LED Panel</p>', 'EcosportGoDau_Gioithieu.webp', '2025-11-26 00:00:00'),
(2, 'Đánh giá của người chơi tại sân cầu lông Ecosport Gò Dầu', 'Đánh giá chi tiết về chất lượng sân bãi, thái độ phục vụ và giá cả...', '<p>Nhiều lông thủ đánh giá cao mặt sân êm, độ bám tốt. Hệ thống đèn LED chống lóa giúp thi đấu buổi tối rất thoải mái.</p>', 'EcosportGoDau_Danhgia.webp', '2025-11-26 00:00:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ai_recommendations`
--
ALTER TABLE `ai_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `recommended_product_id` (`recommended_product_id`);

--
-- Chỉ mục cho bảng `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donhang_id` (`donhang_id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_review` (`user_id`,`sanpham_id`),
  ADD KEY `fk_danhgia_sp` (`sanpham_id`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `kho_voucher`
--
ALTER TABLE `kho_voucher`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lich_su_diem_danh`
--
ALTER TABLE `lich_su_diem_danh`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_sp` (`ma_sp`),
  ADD KEY `thuonghieu_id` (`thuonghieu_id`),
  ADD KEY `danhmuc_id` (`danhmuc_id`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thuonghieu`
--
ALTER TABLE `thuonghieu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thuvienanh`
--
ALTER TABLE `thuvienanh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sanpham_id` (`sanpham_id`);

--
-- Chỉ mục cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ai_recommendations`
--
ALTER TABLE `ai_recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT cho bảng `kho_voucher`
--
ALTER TABLE `kho_voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `lich_su_diem_danh`
--
ALTER TABLE `lich_su_diem_danh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `thuonghieu`
--
ALTER TABLE `thuonghieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `thuvienanh`
--
ALTER TABLE `thuvienanh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=934;

--
-- AUTO_INCREMENT cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ai_recommendations`
--
ALTER TABLE `ai_recommendations`
  ADD CONSTRAINT `fk_ai_sp1` FOREIGN KEY (`product_id`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ai_sp2` FOREIGN KEY (`recommended_product_id`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`donhang_id`) REFERENCES `donhang` (`id`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`);

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `fk_danhgia_sp` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_danhgia_user` FOREIGN KEY (`user_id`) REFERENCES `taikhoan` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`thuonghieu_id`) REFERENCES `thuonghieu` (`id`),
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`danhmuc_id`) REFERENCES `danhmuc` (`id`);

--
-- Các ràng buộc cho bảng `thuvienanh`
--
ALTER TABLE `thuvienanh`
  ADD CONSTRAINT `thuvienanh_ibfk_1` FOREIGN KEY (`sanpham_id`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
