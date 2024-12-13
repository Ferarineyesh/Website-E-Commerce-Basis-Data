-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 05:48 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basis_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `product_id`, `user_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(40, 1, 10000000000001, 2, 2, '160000.00', '2024-11-28 01:00:00', '2024-11-28 01:00:00'),
(41, 1, 10000000000003, 2, 1, '15000.00', '2024-11-28 01:05:00', '2024-11-28 01:05:00'),
(42, 2, 10000000000005, 3, 4, '140000.00', '2024-11-28 02:00:00', '2024-11-28 02:00:00'),
(43, 3, 10000000000007, 4, 2, '50000.00', '2024-11-28 03:00:00', '2024-11-28 03:00:00'),
(44, 4, 10000000000009, 5, 3, '9000.00', '2024-11-28 04:00:00', '2024-11-28 04:00:00'),
(45, 5, 10000000000002, 6, 1, '20000.00', '2024-11-28 05:00:00', '2024-11-28 05:00:00'),
(46, 6, 10000000000004, 7, 5, '60000.00', '2024-11-28 06:00:00', '2024-11-28 06:00:00'),
(47, 7, 10000000000006, 8, 3, '15000.00', '2024-11-28 07:00:00', '2024-11-28 07:00:00'),
(48, 8, 10000000000008, 9, 4, '48000.00', '2024-11-28 08:00:00', '2024-11-28 08:00:00'),
(49, 9, 10000000000010, 10, 1, '15000.00', '2024-11-28 09:00:00', '2024-11-28 09:00:00'),
(50, 0, 10000000000009, 2, 3, '70000.00', '2024-12-09 02:42:46', '2024-12-09 02:42:46'),
(51, 0, 10000000000001, 14, 4, '450.00', '2024-12-13 01:33:42', '2024-12-13 01:33:42'),
(52, 0, 10000000000002, 0, 1, '20000.00', '2024-12-13 01:35:38', '2024-12-13 01:35:38'),
(53, 0, 10000000000002, 0, 1, '20000.00', '2024-12-13 01:36:50', '2024-12-13 01:36:50'),
(54, 0, 10000000000005, 0, 4, '35000.00', '2024-12-13 01:37:00', '2024-12-13 01:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `parent_category_id`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', 'Kategori produk makanan', NULL, '2024-12-04 01:52:43', '2024-12-04 01:52:43'),
(2, 'Minuman', 'Kategori produk minuman', NULL, '2024-12-04 01:52:43', '2024-12-04 01:52:43'),
(3, 'Rumah Tangga', 'Produk kebutuhan rumah tangga', NULL, '2024-12-04 01:52:43', '2024-12-04 01:52:43'),
(4, 'Kebutuhan Sehari-hari', 'Produk kebutuhan pribadi', NULL, '2024-12-04 01:52:43', '2024-12-04 01:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_status` enum('pending','paid','shipped','delivered','canceled') DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_method` enum('credit_card','bank_transfer','cod','e_wallet') DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` text NOT NULL,
  `nama` varchar(25) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` bigint(20) NOT NULL,
  `stok` int(11) NOT NULL,
  `kategori` varchar(20) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanggal_diupdate` date NOT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `nama`, `deskripsi`, `harga`, `stok`, `kategori`, `vendor_id`, `tanggal_dibuat`, `tanggal_diupdate`, `image_url`) VALUES
('10000000000001', 'Beras Merah', 'Beras merah organik 5kg', 80000, 150, 'Makanan', 1, '2024-11-27 15:38:21', '2024-11-25', 'https://down-id.img.susercontent.com/file/id-11134207-7qula-lhpri8ow959m6d'),
('10000000000002', 'Minyak Goreng SunCo', 'Minyak goreng kemasan 1 liter', 20000, 100, 'Makanan', 2, '2024-11-28 04:23:13', '2024-11-25', 'https://laz-img-sg.alicdn.com/p/70300cdf0753a65bc1872971599c0f6e.jpg'),
('10000000000003', 'Gulaku', 'Gula pasir 1kg', 15000, 200, 'Makanan', 3, '2024-11-28 04:23:46', '2024-11-25', 'https://tse3.mm.bing.net/th?id=OIP.m-v3UxX_fSFJiNaBFfKlxAHaHa&pid=Api&P=0&h=180'),
('10000000000004', 'Teh Celup', 'Teh celup merek X 25 kantong', 12000, 250, 'Minuman', 4, '2024-11-27 16:42:56', '2024-11-25', 'https://cf.shopee.co.id/file/ccd140dc29e9820f22a36df7b42b67bc'),
('10000000000005', 'Kopi Bubuk', 'Kopi bubuk 250g', 35000, 120, 'Minuman', 5, '2024-11-27 16:43:34', '2024-11-25', 'https://ecs7.tokopedia.net/img/cache/700/product-1/2020/7/4/310387156/310387156_e72237b9-664c-4f3d-8f8b-48286ef187aa_1512_1512.jpg'),
('10000000000006', 'Air Mineral', 'Air mineral 600ml', 5000, 500, 'Minuman', 6, '2024-11-27 16:44:26', '2024-11-25', 'https://cdn1.productnation.co/optimized/960w/stg/sites/5/5d93142e2a796.jpeg'),
('10000000000007', 'Susu Full Cream', 'Susu full cream 1 liter', 25000, 100, 'Minuman', 7, '2024-11-27 16:45:43', '2024-11-25', 'https://cf.shopee.co.id/file/7cad9653f8e26ce885ac656ed4e73ee4'),
('10000000000008', 'Roti Tawar', 'Roti tawar 400g', 12000, 300, 'Makanan', 8, '2024-11-28 10:44:02', '2024-11-25', 'https://www.luluhypermarket.com/medias/561925-0001.jpg-1200Wx1200H?context=bWFzdGVyfGltYWdlc3w0NDcxNDN8aW1hZ2UvanBlZ3xoNDkvaDIyLzEyMjU5MjI1MDEwMjA2LzU2MTkyNS0wMDAxLmpwZ18xMjAwV3gxMjAwSHxmNzU2ODQ0NWJkMDUzMWVlNjRlNmQ5ZWY4YTk0ODIwODg0NDFmYjgyNGYwOTgzMzE2NDI2ZjA3YTJiZWEwY2Uy'),
('10000000000009', 'Mie Instan', 'Mie instan rasa ayam 80g', 3000, 500, 'Makanan', 9, '2024-11-27 16:47:12', '2024-11-25', 'https://down-id.img.susercontent.com/file/973479fc7a5ef8729df97225070a289d'),
('10000000000010', 'Sayuran Segar', 'Sayuran segar, berbagai jenis, 500g', 15000, 250, 'Makanan', 10, '2024-11-27 16:50:55', '2024-11-25', 'https://cf.shopee.co.id/file/72785aed66b9e0d41d3693cb3586f900'),
('10000000000011', 'Pembersih Lantai', 'Pembersih lantai merek Z 1 liter', 22000, 180, 'Rumah Tangga', 11, '2024-11-27 16:52:01', '2024-11-25', 'https://tse1.mm.bing.net/th?id=OIP.FzQ0G4dtFhHdTR7PJEihbQHaHa&pid=Api&P=0&h=180'),
('10000000000012', 'Detergen Bubuk', 'Detergen bubuk 500g', 10000, 220, 'Rumah Tangga', 12, '2024-11-27 16:52:34', '2024-11-25', 'https://cdnapi.sooplai.com/media/products/234103/4ddc952f-1aa.png'),
('10000000000013', 'Sabun Cuci Piring', 'Sabun cuci piring merek Y 500ml', 8000, 300, 'Rumah Tangga', 13, '2024-11-27 16:56:16', '2024-11-25', 'https://cf.shopee.co.id/file/2191c4d68b20cd72f4e82aed4d6b29a9'),
('10000000000014', 'Tissue Toilet', 'Tissue toilet 10 roll', 25000, 400, 'Rumah Tangga', 14, '2024-11-27 16:56:52', '2024-11-25', 'https://cdn.shopify.com/s/files/1/0260/6530/5645/products/321406_1024x.jpg?v=1627522991'),
('10000000000015', 'Sampo', 'Sampo anti ketombe 400ml', 18000, 150, 'Kebutuhan Sehari-har', 15, '2024-11-27 16:57:30', '2024-11-25', 'https://down-id.img.susercontent.com/file/ec6ef33c57fc9d2fdb264491fd5161b4'),
('10000000000016', 'Odol', 'Pasta gigi 100g', 7000, 500, 'Kebutuhan Sehari-har', 16, '2024-11-27 16:59:57', '2024-11-25', 'https://cf.shopee.co.id/file/f65e0166a376ddada84f7874156895eb'),
('10000000000017', 'Handuk', 'Handuk mandi ukuran besar', 35000, 100, 'Kebutuhan Sehari-har', 17, '2024-11-27 17:01:59', '2024-11-25', 'https://tse2.mm.bing.net/th?id=OIP.AmAK4YhiN4EPVP-U4u-jyAHaHa&pid=Api&P=0&h=180'),
('10000000000018', 'Pembalut', 'Pembalut wanita 10 pcs', 15000, 200, 'Kebutuhan Sehari-har', 18, '2024-11-27 17:02:47', '2024-11-25', 'https://cf.shopee.co.id/file/ebae41545721ac8f8e167c2a1c2469e3'),
('10000000000019', 'Tisu Wajah', 'Tisu wajah lembut 80 lembar', 10000, 250, 'Kebutuhan Sehari-har', 19, '2024-11-27 17:03:21', '2024-11-25', 'https://cf.shopee.co.id/file/1443903a94e80cf0f208384a60746cef'),
('10000000000020', 'Obat Batuk', 'Obat batuk 100ml', 15000, 120, 'Kebutuhan Sehari-har', 20, '2024-11-27 17:04:32', '2024-11-25', 'https://www.static-src.com/wcsstore/Indraprastha/images/catalog/full/97/MTA-1449690/woods_woods-peppermint-expectorant-obat-batuk--100-ml-_full02.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `role`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', 'hashed_password', '081234567890', 'Jl. Mawar 1', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', 'hashed_password', '081298765432', 'Jl. Melati 2', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(3, 'Alice', 'Brown', 'alice.brown@example.com', 'hashed_password', '081233344455', 'Jl. Anggrek 3', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(4, 'Bob', 'Johnson', 'bob.johnson@example.com', 'hashed_password', '081244455566', 'Jl. Kenanga 4', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(5, 'Charlie', 'Davis', 'charlie.davis@example.com', 'hashed_password', '081255566677', 'Jl. Dahlia 5', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(6, 'Emily', 'Clark', 'emily.clark@example.com', 'hashed_password', '081266677788', 'Jl. Sakura 6', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(7, 'David', 'Evans', 'david.evans@example.com', 'hashed_password', '081277788899', 'Jl. Seruni 7', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(8, 'Sophia', 'Taylor', 'sophia.taylor@example.com', 'hashed_password', '081288899900', 'Jl. Srikandi 8', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(9, 'Ethan', 'Wilson', 'ethan.wilson@example.com', 'hashed_password', '081299900011', 'Jl. Bunga Raya 9', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(10, 'Isabella', 'Moore', 'isabella.moore@example.com', 'hashed_password', '081210101112', 'Jl. Anyelir 10', 'customer', '2024-12-08 18:20:54', '2024-12-08 18:20:54'),
(11, 'Michael Purba', '', 'Michael@gmail.com', '$2y$10$3MhwWf23Vo1PNCSmPPIl/uJitEKy7wIIgVvTY9UKdQasi7H9HY.1G', NULL, NULL, 'customer', '2024-12-09 08:34:43', '2024-12-09 08:34:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`(14));

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
