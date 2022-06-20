-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 20, 2022 lúc 06:02 PM
-- Phiên bản máy phục vụ: 10.4.19-MariaDB
-- Phiên bản PHP: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `internproject`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2022_06_17_072903_create_product_table', 1),
(3, '2022_06_19_154748_add_image_field', 1),
(4, '2022_06_20_034448_add_field_description', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('pending','reject','approve') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `status`, `image`, `description`) VALUES
(1, 'Áo thun', 92971, 61, 'pending', '1655737614.png', 'Et illum veritatis sint non sit inventore. Fuga maiores porro sint.'),
(2, 'Quần jean', 20318, 177, 'pending', '1655737589.jpg', 'Molestias itaque repellendus quia et rem id quis ea. Est dolore molestias delectus molestias aperiam accusantium cumque. Repellat repudiandae laudantium non tempora et.'),
(3, 'Áo sơmi', 41439, 85, 'reject', '', 'Sapiente et non blanditiis fugiat. Rerum magnam cupiditate et qui quia enim. Id ut asperiores voluptas.'),
(4, 'Giày', 91911, 58, 'reject', '', 'Adipisci quisquam qui delectus cupiditate omnis quia rerum aut. Delectus molestiae eos aliquid ut. Et sit exercitationem dolor earum eaque est.'),
(5, 'Giày cao gót', 87245, 164, 'pending', '1655737739.png', 'Nostrum illum eum velit voluptatem eligendi quia. Qui ducimus autem et hic ex. Suscipit fugit voluptatem et.'),
(6, 'Quần short', 34691, 14, 'approve', '1655737578.jpg', 'Deserunt ut similique dolore unde nam laudantium. Dolore odit iure fuga officia nostrum eius voluptates. Quas qui nesciunt nostrum. Qui quod mollitia nobis assumenda voluptas eligendi omnis.'),
(7, 'Áo ba lỗ', 40133, 141, 'reject', '1655737684.jpg', 'Architecto dolor libero ex veritatis reiciendis. Laudantium at nobis qui totam enim. Sunt distinctio officia enim.'),
(8, 'Nón', 494, 159, 'approve', '1655737715.jpg', 'Voluptatem pariatur rerum consequuntur blanditiis harum. Ex qui accusantium architecto molestiae sunt laboriosam sit. Voluptas consequatur voluptates quod deserunt. Molestiae velit at dignissimos et.'),
(9, 'Dây chuyền', 5000000, 50, 'approve', '1655737635.png', 'Chế tác tinh xảo, tỉ mỉ, cầu kỳ từng chi tiết\r\nDây mĩ kí mạ vàng 18k\r\nDây dạng xoắn độc đáo, mới lạ\r\nThiết kế đơn giản mà sang trọng, tinh tế\r\nTôn nét đẹp thời thượng của nam gioi hiện đại\r\nPhối hợp đẹp mắt với các loại quần áo, trang phục\r\nTrang sức là vật bất ly thân của mọi cô gái, tôn lên nét đẹp duyên dáng, cá tính, hay sang trọng của phái nữ. Dây chuyền, vòng cổ nữ dạng xoắn là một sản phẩm đặc sắc nằm trong bộ sưu tập phụ kiện thời trang, trang sức  mà mn Shop đang bán trên thị trường nói chung. \r\nDây chuyền dạng xoắn, được mạ vàng 18K với chế tác tinh xảo, cầu kỳ, đẹp mắt sẽ làm nổi bật người đeo nó. Với sản phẩm này bạn hoàn toàn tự tin dạo bước đi bất cứ nơi đâu.\r\nSản phẩm phù hợp làm quà tặng cho người yêu, người thân, tặng chị, tặng mẹ nhân dịp ngày lễ lớn trong năm.');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_name_unique` (`name`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
