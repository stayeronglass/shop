-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 14 2019 г., 20:42
-- Версия сервера: 5.7.20
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `discount`
--

-- --------------------------------------------------------

--
-- Структура таблицы `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `amount` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `main` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `root` int(11) DEFAULT NULL,
  `lvl` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `image_id`, `parent_id`, `name`, `slug`, `description`, `main`, `created_at`, `updated_at`, `root`, `lvl`, `lft`, `rgt`, `deleted_at`) VALUES
(-2, NULL, NULL, 'Age of Sigmar', 'age_of_sigmar', 'Age of Sigmar', 0, '2019-07-13 23:31:28', '2019-07-13 23:31:28', 6, 0, 1, 10, NULL),
(-1, NULL, NULL, 'Warhammer40000', 'warhammer40000', 'Warhammer40000', 0, '2019-07-13 23:31:17', '2019-07-13 23:31:17', 5, 0, 1, 10, NULL),
(1, NULL, -1, 'The Horus Heresy: Betrayal at Calth Bits', 'the_horus_heresy_betrayal_at_calth_bits', 'The Horus Heresy: Betrayal at Calth Bits', 1, '2019-07-13 23:04:34', '2019-07-13 23:34:47', 5, 1, 8, 9, NULL),
(2, NULL, -1, 'Dark Vengeance Bits', 'dark_vengeance_bits', 'Dark Vengeance Bits', 1, '2019-07-13 23:04:49', '2019-07-13 23:34:54', 5, 1, 6, 7, NULL),
(3, NULL, -1, 'The Horus Heresy: Burning of Prospero Bits', 'the_horus_heresy_burning_of_prospero_bits', 'The Horus Heresy: Burning of Prospero Bits', 1, '2019-07-13 23:05:26', '2019-07-13 23:35:04', 5, 1, 4, 5, NULL),
(4, NULL, -1, 'Dark Imperium/Know no Fear Bits', 'dark_imperium_know_no_fear_bits', 'Dark Imperium/Know no Fear Bits', 1, '2019-07-13 23:05:39', '2019-07-13 23:35:10', 5, 1, 2, 3, NULL),
(7, NULL, -2, 'Age of Sigmar Soul Wars Bitz', 'age_of_sigmar_soul_wars_bitz', 'Age of Sigmar Soul Wars Bitz', 1, '2019-07-13 23:36:23', '2019-07-13 23:36:23', 6, 1, 2, 3, NULL),
(8, NULL, -2, 'Age of Sigmar: Blightwar Bitz', 'age_of_sigmar_blightwar_bitz', 'Age of Sigmar: Blightwar Bitz', 1, '2019-07-13 23:36:39', '2019-07-13 23:36:39', 6, 1, 4, 5, NULL),
(9, NULL, -2, 'Age of Sigmar Starter Set Bitz', 'age_of_sigmar_starter_set_bitz', 'Age of Sigmar Starter Set Bitz', 1, '2019-07-13 23:36:56', '2019-07-13 23:36:56', 6, 1, 6, 7, NULL),
(10, NULL, -2, 'Age of Sigmar: Spire Dawn Starter Bitz', 'age_of_sigmar_spire_dawn_starter_bitz', 'Age of Sigmar: Spire Dawn Starter Bitz', 1, '2019-07-13 23:37:25', '2019-07-13 23:37:57', 6, 1, 8, 9, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `fos_user`
--

CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ext` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `key_value`
--

CREATE TABLE `key_value` (
  `key` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `key_value`
--

INSERT INTO `key_value` (`key`, `description`, `value`, `created_at`, `updated_at`, `deleted_at`) VALUES
('description', 'description', 'description', '2019-07-13 23:08:43', '2019-07-13 23:08:43', NULL),
('keywords', 'keywords', 'keywords', '2019-07-13 23:08:49', '2019-07-13 23:08:49', NULL),
('main_page_text', 'main_page_text', 'Купить warhammer 40000 дешево', '2019-07-13 23:21:40', '2019-07-13 23:21:40', NULL),
('russian_name', 'russian_name', 'Магазинчик \"Вархаммер дисконт\"', '2019-07-13 23:13:59', '2019-07-13 23:17:22', NULL),
('site_name', 'title', 'Warhammer Discount', '2019-07-13 23:08:27', '2019-07-13 23:08:27', NULL),
('vk', 'vk', 'https://vk.com/public184430357', '2019-07-13 23:13:09', '2019-07-13 23:13:09', NULL),
('yandex_metrica', 'yandex_metrica', '54345313', '2019-07-13 23:09:44', '2019-07-13 23:09:44', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Games Workshop', 'Games Workshop', '2019-07-13 21:08:39', '2019-07-13 21:08:39');

-- --------------------------------------------------------

--
-- Структура таблицы `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `root` int(11) DEFAULT NULL,
  `lvl` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `total` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `order_status`
--

CREATE TABLE `order_status` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `title` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `sale_price` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `short` longtext COLLATE utf8mb4_unicode_ci,
  `out_of_stock` tinyint(1) DEFAULT NULL,
  `new` tinyint(1) NOT NULL,
  `banner` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `provider`
--

CREATE TABLE `provider` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `provider`
--

INSERT INTO `provider` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'miniaturesfan', 'miniaturesfan', '2019-07-13 21:41:02', '2019-07-13 21:41:02', NULL),
(2, 'Кладбище Мертвых Солдатиков', 'Кладбище Мертвых Солдатиков', '2019-07-14 15:05:57', '2019-07-14 15:05:57', NULL),
(3, 'Алегрис', 'Алегрис', '2019-07-14 15:06:04', '2019-07-14 15:06:04', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_extra`
--

CREATE TABLE `user_extra` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D4E6F81A76ED395` (`user_id`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BA388B7A76ED395` (`user_id`),
  ADD KEY `IDX_BA388B74584665A` (`product_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64C19C1989D9B62` (`slug`),
  ADD UNIQUE KEY `UNIQ_64C19C13DA5256D` (`image_id`),
  ADD KEY `IDX_64C19C1727ACA70` (`parent_id`);

--
-- Индексы таблицы `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `fos_user`
--
ALTER TABLE `fos_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`);

--
-- Индексы таблицы `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045F4584665A` (`product_id`);

--
-- Индексы таблицы `key_value`
--
ALTER TABLE `key_value`
  ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307FF624B39D` (`sender_id`),
  ADD KEY `IDX_B6BD307FE92F8F78` (`recipient_id`),
  ADD KEY `IDX_B6BD307F727ACA70` (`parent_id`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`),
  ADD KEY `IDX_F52993986BF700BD` (`status_id`);

--
-- Индексы таблицы `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D1C0D9008D9F6D38` (`order_id`);

--
-- Индексы таблицы `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_140AB620989D9B62` (`slug`);

--
-- Индексы таблицы `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04ADA23B42D` (`manufacturer_id`),
  ADD KEY `IDX_D34A04ADE308AC6F` (`material_id`);
ALTER TABLE `product` ADD FULLTEXT KEY `IDX_D34A04AD2B36786B` (`title`);

--
-- Индексы таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `IDX_CDFC73564584665A` (`product_id`),
  ADD KEY `IDX_CDFC735612469DE2` (`category_id`);

--
-- Индексы таблицы `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_extra`
--
ALTER TABLE `user_extra`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_AFFDF63DA76ED395` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `fos_user`
--
ALTER TABLE `fos_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `provider`
--
ALTER TABLE `provider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user_extra`
--
ALTER TABLE `user_extra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_BA388B74584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_BA388B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`);

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C13DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_64C19C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B6BD307FE92F8F78` FOREIGN KEY (`recipient_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F52993986BF700BD` FOREIGN KEY (`status_id`) REFERENCES `order_status` (`id`),
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `FK_D1C0D9008D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`);

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04ADA23B42D` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`),
  ADD CONSTRAINT `FK_D34A04ADE308AC6F` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`);

--
-- Ограничения внешнего ключа таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_CDFC735612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CDFC73564584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_extra`
--
ALTER TABLE `user_extra`
  ADD CONSTRAINT `FK_AFFDF63DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
