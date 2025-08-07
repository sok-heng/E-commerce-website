-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 01, 2025 at 12:45 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment`
--
CREATE DATABASE IF NOT EXISTS `assignment` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `assignment`;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `message`, `submitted_at`, `user_id`) VALUES
(1, 'mai', 'mai123@gmail.com', '0213213232', 'nh ot jol niyeay jkren refund vinh oy lern tic', '2025-07-30 21:15:32', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `card_last4` char(4) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `shipping` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description1` text DEFAULT NULL,
  `description2` text DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT '(''face'', ''body'', ''hair'')',
  `image_url` text DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description1`, `description2`, `type`, `image_url`, `stock`) VALUES
(5, 'Anua Heartleaf', 12.00, 'Quercetinol Facial Deep Pore Cleansing Foam For Oily & Combination Skin 150 mL', 'Anua\'s Heartleaf Quercetinol Pore Deep Cleansing Foam is a gentle yet effective cleanser formulated for oily and combination skin types. It deeply cleanses pores, removes impurities, and balances sebum production without stripping the skin\'s natural moisture. ', 'face', 'uploads/1cdfb51fa588f6789d4557cf3f0912ef.jpg', 8),
(6, 'Anua Heartleaf Pore', 10.00, 'Cleansing Oil Mild, Sensitive Skin Face Wash, Oil Cleanser, Makeup Blackhead Remover, Korean Skin Care, Non-Comedogenic, Fragrance Free 6.76 Fl Oz (200mL)', 'Anua\'s Heartleaf Pore Cleansing Oil Mild is a lightweight, non-comedogenic cleanser formulated specifically for sensitive skin. It effectively removes impurities, makeup, sunscreen, and excess sebum without clogging pores, leaving the skin refreshed and hydrated. ', 'face', 'uploads/73778c1a1da4f5bdc42121964041a299.jpg', 10),
(7, 'Anua Niacinamide', 25.00, 'Anua Niacinamide 10% + TXA 4% Serum 30 mL This serum combines 10% Niacinamide and 4% Tranexamic Acid to effectively ', 'The Anua Niacinamide 10% + TXA 4% Serum is a lightweight, non-sticky formula designed to target dark spots and hyperpigmentation, promoting a clearer and more radiant complexion. This serum combines 10% Niacinamide and 4% Tranexamic Acid to effectively address skin discoloration and uneven tone. ', 'face', 'uploads/ebb7f92532b84c6d35cbd7dd8f32224f.jpg', 11),
(8, 'ANUA Rice', 18.00, 'ANUA Rice 70 Intensive Moisturizing Milk 150 mL leaving the skin soft, smooth and radiant without any greasy residue.\r\n', 'Anua Rice 70 Intensive Moisturizing Milk is a lightweight yet deeply nourishing lotion enriched with 70% Rice Extract to provide intense hydration and improve skin texture. Formulated with Niacinamide and Ceramides, it helps to brighten the complexion while strengthening the skin barrier. Its milky texture absorbs quickly, leaving the skin soft, smooth, and radiant without any greasy residue. Ideal for all skin types, including dry and sensitive skin.', 'face', 'uploads/8fcdf23754145f928a16f6b6495b2b60.jpg', 10),
(9, 'Anua Green', 30.00, 'Anua Green Lemon Vitamin C Blemish Serum 20 g  provide lasting hydration for a healthier complexion. ', 'The Anua Green Lemon Vitamin C Blemish Serum is a brightening and skin-repairing serum formulated with Green Lemon Extract and Vitamin C to fade dark spots, even out skin tone, and boost radiance. Enriched with Niacinamide and Centella Asiatica, this lightweight serum helps reduce blemishes, rejuvenate dull skin strengthen the skin barrier, and provide lasting hydration for a healthier complexion. ', 'face', 'uploads/f3a181f2b8204cd83bc1d053a592cea2.jpg', 10),
(10, 'Anua Peach 77%', 38.00, 'Anua Peach 77% Niacinamide Enriched Moisturizing Face Cream For All Skin Types 50 mL\r\n', 'This lightweight yet deeply hydrating face cream is infused with 77% Peach Extract and 5% Niacinamide, delivering intense hydration while brightening and smoothing the skin. The formula also contains Panthenol and Hyaluronic Acid to strengthen the skin barrier and retain moisture, making it ideal for all skin types. Its non-greasy, fast-absorbing texture leaves the skin soft, plump, and radiant.', 'face', 'uploads/624f2a3407df3217ae7a603e01a97276.jpg', 10),
(11, 'Anua Ultra ', 10.00, 'Anua Ultra Thin Spot Cover Patch 75EA .These patches help reduce inflammation, promote faster healing, and prevent bacteria from worsening blemishes.\r\n', 'The Anua Ultra Thin Spot Cover Patch is a hydrocolloid pimple patch designed to protect, heal, and conceal breakouts while absorbing excess pus and impurities. Its ultra-thin design ensures a natural, barely visible finish, making it ideal for daytime wear under makeup. These patches help reduce inflammation, promote faster healing, and prevent bacteria from worsening blemishes.', 'face', 'uploads/834902f5bf4534c322422b840ef22259.jpg', 8),
(12, 'IKT Hair', 6.00, 'IKT Hair Wax Stick 75g ,this wax stick is easy to apply and perfect for edge control, slicked-back styles, and defining texture.', 'The IKT Hair Wax Stick (75g) is a lightweight, non-greasy styling product designed to smooth flyaways, tame frizz, and provide flexible hold for sleek hairstyles. It delivers natural shine and long-lasting control without leaving a stiff or sticky residue. Ideal for all hair types, including curly, straight, and wavy hair, this wax stick is easy to apply and perfect for edge control, slicked-back styles, and defining texture.', 'hair', 'uploads/90f2ce41c63b371cf8be01bbba252e02.jpg', 10),
(13, 'WOW Dream', 28.00, 'WOW Dream Coat Supernatural Spray 200 mL ,This lightweight formula ensures hair remains smooth and vibrant without any greasy residue.', 'The Color Wow Dream Coat Supernatural Spray is an innovative anti-frizz treatment designed to combat humidity and transform all hair types into sleek, glossy strands. Utilizing advanced heat-activated polymer technology, it creates an invisible shield around each hair fiber, effectively blocking moisture and preventing frizz for up to three to four shampoos. This lightweight formula ensures hair remains smooth and vibrant without any greasy residue.', 'hair', 'uploads/dc80e92d6254d4d914dd9a1dc3dbb18c.jpg', 10),
(14, 'Fino premium', 25.00, 'Fino premium touch hair mask 230g  it penetrates deep into the hair shaft to provide intensive hydration, strength, and shine.\r\n', 'The Shiseido Fino Premium Touch Hair Mask is a high-performance treatment designed to revitalize and repair dry, damaged hair. Formulated with a blend of potent ingredients, it penetrates deep into the hair shaft to provide intensive hydration, strength, and shine.', 'hair', 'uploads/30d693b323efd160cb57069bc487b431.jpg', 9),
(15, '&honey Melty', 25.00, '&honey Melty Moist Repair Oil 100mL , It absorbs quickly without leaving a greasy residue, leaving your hair soft, silky, and manageable.\r\n', '&honey Melty Moist Repair Oil is a deeply hydrating and repairing hair oil designed for frizzy, unruly, and damaged hair. Enriched with honey, botanical oils, and ceramides, this lightweight yet nourishing formula tames frizz, restores moisture, and enhances hair elasticity. It absorbs quickly without leaving a greasy residue, leaving your hair soft, silky, and manageable.', 'hair', 'uploads/b2280d851dfa188dea76ce74c046c236.jpg', 10),
(16, 'mixsoon Scalp', 24.00, 'mixsoon Scalp & Hair Essence 50 mL ,it helps strengthen hair follicles and promote healthy hair growth.\r\n', 'Mixsoon Scalp & Hair Essence is a versatile K-beauty solution targeting both scalp health and hair vitality. This lightweight essence contains fermented rice water, a traditional ingredient known for its nourishing properties. Rich in vitamins, minerals, and amino acids, it helps strengthen hair follicles and promote healthy hair growth.', 'hair', 'uploads/c4389644a01c9673e1ff8693c59bcdfb.jpg', 10),
(17, '&honey Deep Moist', 29.00, '&honey Deep Moist Treatment 440mL ,  this treatment locks in hydration while keeping hair smooth, soft, and frizz-free.', '&HONEY Deep Moist Hair Treatment is a luxurious, ultra-hydrating hair treatment designed to deeply nourish and repair dry, damaged hair. Infused with a blend of honey-based ingredients and botanical extracts, it helps to restore moisture balance, enhance shine, and improve hair manageability. With a high moisture content formula, this treatment locks in hydration while keeping hair smooth, soft, and frizz-free.', 'hair', 'uploads/0f1f75ae91edbae95094762a7abd5b2a.jpg', 10),
(18, 'Beauty of Joseon', 19.00, 'Beauty of Joseon Apricot Blossom Peeling Gel For Face And Body 120 mL ,The inclusion of prunus mume flower water for moisturization and cellulose for physical exfoliation appears to be the key components in this product.', 'Beauty of Joseon Apricot Blossom Peeling Gel offers a unique combination of exfoliation and moisturization, making it suitable for various skin types, including sensitive skin. The inclusion of prunus mume flower water for moisturization and cellulose for physical exfoliation appears to be the key components in this product.', 'body', 'uploads/a93dc22b365e927c6937f470fb4670fb.jpg', 10),
(19, 'Beauty of Joseon Jello', 18.00, 'Beauty of Joseon Jello Skin Massage Cream 200 mL , mental tensions through therapeutic massage.', 'Drawing inspiration from Hanbang\'s principle that a balanced mind and body are key to well-being, we created the JELLOSKIN Massage Cream. This cream is designed not just to refine facial contours but to soothe and release the physical and mental tensions through therapeutic massage.', 'body', 'uploads/f0489150e96f7347a59f1bf4f36fb3aa.jpg', 9),
(20, 'Mom’s Bath', 80.00, 'Mom’s Bath Recipe Body Peeling Pad Trouble Care (8 Sheets), It features a dual-textured pad—one side for deeper scrubbing and the other for a gentler touch. Ideal for oily, acne-prone, or sensitive skin.', 'The Mom’s Bath Recipe Body Peeling Pad Trouble Care is a specially designed exfoliating body pad formulated to gently remove dead skin cells, excess oil, and impurities while soothing troubled or acne-prone skin. Inspired by Korean bathhouse (jjimjilbang) scrubs, this peeling pad contains anti-inflammatory and calming ingredients that help reduce body breakouts while maintaining skin hydration. It features a dual-textured pad—one side for deeper scrubbing and the other for a gentler touch. Ideal for oily, acne-prone, or sensitive skin.', 'body', 'uploads/3eb95d4a70fe15b8633e0855db0cb84e.jpg', 9),
(21, 'SOME BY MI AHA', 32.00, 'SOME BY MI AHA BHA PHA 30 Days Miracle clear body cleanser 400g , it ideal for acne-prone and sensitive skin.', 'The SOME BY MI AHA BHA PHA 30 Days Miracle Clear Body Cleanser is a gentle yet effective exfoliating body wash formulated to target body acne, rough texture, and clogged pores. Infused with a blend of AHA, BHA, and PHA, it helps remove dead skin cells, control excess sebum, and promote clearer, smoother skin. Additionally, Centella Asiatica and Tea Tree Extract work to soothe irritation and prevent breakouts, making it ideal for acne-prone and sensitive skin.', 'body', 'uploads/bcd4bbc29a370e0e3ddc307e50b3b113.jpg', 10),
(22, 'ACM SEBIONEX', 16.00, 'ACM SEBIONEX Purifying Dermatological Bar 100g , it suitable for daily use on both the face and body.', 'The ACM SEBIONEX Purifying Dermatological Bar is a soap-free cleansing bar specially formulated for oily and acne-prone skin. It gently cleanses while regulating excess sebum, helping to prevent breakouts and leaving the skin feeling fresh and purified. With its mild yet effective formulation, it removes impurities and excess oil without drying out the skin, making it suitable for daily use on both the face and body.', 'body', 'uploads/0a4adc79add218fab4faf51ae8f142d1.jpg', 9);

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `title` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `title`, `description`, `rating`, `image_path`, `created_at`) VALUES
(9, 18, 1, 'best skin i ever use', 'ber min jer sak merl sin tv sak hz ng jong sak tt ber min tinh sak muy tv nh bra os 100 ler hz xd, that actually really i swear after i use this product my face become uglier even in the mirror hope someone buy it', 1, NULL, '2025-07-31 05:10:48'),
(10, 8, 2, 'Godly product', 'Bought this thinking it was just another lotion — turns out it&#039;s skincare sent from the heavens.\r\nMy acne saw it and immediately packed its bags.\r\nNow my face is smoother than my WiFi on payday.\r\nEven my mom asked what filter I’m using — it&#039;s called &quot;this cream, queen.&quot;\r\nIf this product had legs, I’d marry it. No prenup.', 3, 'uploads/reviews/688a9944c59db__ (6).jpeg', '2025-07-31 05:14:28'),
(11, 9, 4, 'product bra min kert', 'This cream did absolutely nothing… except give my wallet emotional damage.\r\nApplied it religiously for 2 weeks — now my pimples have their own zip code.\r\nSmells like hope, works like disappointment.\r\nEven my mirror sighed at me this morning.\r\nWould I recommend it? Only if you&#039;re beefing with someone&#039;s skin.', 2, 'uploads/reviews/688a9a827be84__ (8).jpeg', '2025-07-31 05:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(45) DEFAULT NULL,
  `user_email` varchar(45) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_role` varchar(45) NOT NULL DEFAULT 'user',
  `user_phone` varchar(45) DEFAULT NULL,
  `user_location` varchar(45) DEFAULT NULL,
  `user_profile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_role`, `user_phone`, `user_location`, `user_profile`) VALUES
(1, 'Ean sokheng', 'henglih574@gmail.com', '$2y$10$YIBrglBgQsc7YA5JHkJyJOwOQ52oqOElH8oFETzxiLn6FzLq5pURS', 'admin', '02131312', 'phnom penh', 'uploads/profile_688a77dc29e849.01359464.jpg'),
(2, 'mai', 'mai123@gmail.com', '$2y$10$ThwIK/3fkbIbWmJmaJDrNuy9MJ0f5CPuRn1ZqFZ9n3qxVNdhD4LcS', 'user', '012312313', 'phnom penh', 'uploads/profile_688a8af98d1560.60640011.jpg'),
(4, 'chheng', 'chheng@gmail.com', '$2y$10$3aC2VL3hWpwBwaaBWoE8LOSPZQv5IhCBbc7oNQKdwfDfWUysQSjpO', 'user', '013132132132131', 'phnom penh', 'uploads/profile_688a999ed813e0.63718776.jpg'),
(5, 'Anua Birch', 'kakaka@gmail.com', '$2y$10$Gj8HxXgV82EicxG.W3.XJONO.76bxZ3brLjNcRCEH/VvtA3A0dyRu', 'user', '02131321', 'phnom penh', 'uploads/profile_688bf95f0cbcb5.05379629.gif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
