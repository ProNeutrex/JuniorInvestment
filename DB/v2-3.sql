-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 31, 2023 at 08:06 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hyipv2-2`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--




ALTER TABLE `blogs`
    ADD COLUMN `locale_id` int DEFAULT NULL,
    ADD COLUMN `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en';


-- Update the `locale_id` based on the primary key `id`
UPDATE `blogs` SET `locale_id` = `id`;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_methods`
--

CREATE TABLE `deposit_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `gateway_id` int UNSIGNED DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('auto','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `gateway_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge` double NOT NULL DEFAULT '0',
  `charge_type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_deposit` double NOT NULL,
  `maximum_deposit` double NOT NULL,
  `rate` double NOT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--
DROP TABLE IF EXISTS `gateways`;

CREATE TABLE `gateways` (
    `id` bigint UNSIGNED NOT NULL,
    `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `gateway_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `supported_currencies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    `credentials` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `is_withdraw` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
    `status` tinyint(1) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `logo`, `name`, `gateway_code`, `supported_currencies`, `credentials`, `is_withdraw`, `status`, `created_at`, `updated_at`) VALUES
(1, 'global/images/x2KBMjdkKOxVojM1BLpg.svg', 'Paypal', 'paypal', '[\"USD\", \"EUR\", \"GBP\", \"CAD\", \"AUD\", \"JPY\", \"SGD\", \"NZD\", \"CHF\", \"SEK\", \"NOK\", \"DKK\", \"PLN\", \"HUF\", \"CZK\", \"ILS\", \"BRL\", \"MXN\", \"HKD\", \"TWD\", \"TRY\", \"INR\", \"RUB\", \"ZAR\", \"MYR\", \"THB\", \"IDR\", \"PHP\", \"NGN\", \"GHS\"]', '{\"client_id\":\"AUnfkQ3v2J-d5g-ZEq8l5Oosha8FmAJ3Z9jt71BkD6l-Z3FMIR5FmkYp_6QHwaYx0LvMNvHDsB9Vh84O\",\"client_secret\":\"EJjci35CFj762ut15pn0VWmojEG0GwE68byyHnBq_NoAXXW9mjkjdLFYLfVQwyLs8QAbP1QJAXPl5oAl\",\"app_id\":\"APP-80W284485P519543T\",\"mode\":\"sandbox\"}', 'paypal_email', 1, NULL, '2023-06-20 16:51:45'),
(2, 'global/gateway/stripe.png', 'Stripe', 'stripe', '[\"USD\",\"AUD\",\"BRL\",\"CAD\",\"CHF\",\"DKK\",\"EUR\",\"GBP\",\"HKD\",\"INR\",\"JPY\",\"MXN\",\"MYR\",\"NOK\",\"NZD\",\"PLN\",\"SEK\",\"SGD\"]', '{\"stripe_key\":\"pk_test_51KHQhKAmfDlh6wQq4srkOEY3FkivTCXmRSb7bJqr90q3ZkVWAR2AkRWfKBnegpmKAHea5cNVAToiy7yoa3Q075mR00jlhXsZTO\",\"stripe_secret\":\"sk_test_51KHQhKAmfDlh6wQqXfg4ZScnTRahxbdXV0mKw30nOI4f8gtB2v5rho7IyJtZqkf8SwwuNgLTO2WPGFyk9vnFl8gO00MhSe8Kbj\"}', '0', 1, NULL, '2022-11-13 07:46:46'),
(3, 'global/gateway/mollie.png', 'Mollie', 'mollie', '[\"EUR\", \"USD\", \"GBP\", \"CAD\", \"AUD\", \"CHF\", \"DKK\", \"NOK\", \"SEK\", \"PLN\", \"CZK\", \"HUF\", \"RON\", \"BGN\", \"HRK\", \"ISK\", \"ZAR\"]', '{\"api_key\":\"test_intSTCDEBaDSu28D6DUpn5wnQhTnzB\"}', '0', 1, NULL, '2022-10-28 15:43:50'),
(4, 'global/gateway/perfectmoney.png', 'Perfect Money', 'perfectmoney', '[\"USD\", \"EUR\", \"RUB\", \"UAH\"]', '{\"PM_ACCOUNTID\":\"96793260\",\"PM_PASSPHRASE\":\"77887848a\",\"PM_MARCHANTID\":\"U36928259\",\"PM_MARCHANT_NAME\":\"tdevs\"}', '0', 1, NULL, '2023-06-19 13:01:24'),
(5, 'global/gateway/coinbase.png', 'Coinbase', 'coinbase', '[\"USD\", \"EUR\", \"GBP\", \"CAD\", \"AUD\", \"JPY\", \"BTC\", \"ETH\", \"LTC\", \"BCH\", \"XRP\", \"EOS\"]', '{\"apiKey\":\"8ef6c4ca-f5c7-4717-9d9a-002adf7e7590\",\"account_id\":\"\",\"private_key\":\"\",\"webhookSecret\":\"b789f547-8954-4880-89ae-5a0233006647\",\"apiVersion\":\"2018-03-22\"}', 'email_address', 1, NULL, '2022-10-28 19:16:15'),
(6, 'global/gateway/paystack.png', 'Paystack', 'paystack', '[\"NGN\", \"USD\", \"GBP\", \"EUR\", \"GHS\", \"KES\", \"ZAR\", \"UGX\", \"TZS\", \"RWF\"]', '{\"public_key\":\"pk_test_8e60e513e47ba5619ac0888c9fac99f2853641fa\",\"secret_key\":\"sk_test_e521a3c6d1c37897092868e02e0ddba8c3f0aa01\",\"merchant_email\":\"learn2222earn@gmail.com\"}', '0', 1, NULL, '2022-12-17 09:18:45'),
(7, 'global/gateway/voguepay.png', 'Voguepay', 'voguepay', '[\"USD\", \"EUR\", \"GBP\", \"NGN\", \"GHS\", \"KES\", \"ZAR\", \"UGX\", \"TZS\", \"RWF\"]', '{\"merchant_id\":\"sandbox_760e43f202878f651659820234cad9\"}', '0', 1, NULL, '2022-11-14 02:08:13'),
(8, 'global/gateway/flutterwave.png', 'Flutterwave', 'flutterwave', '[\"USD\", \"EUR\", \"GBP\", \"NGN\", \"GHS\", \"KES\", \"ZAR\", \"UGX\", \"TZS\", \"RWF\", \"CAD\", \"AUD\", \"JPY\", \"INR\"]', '{\"public_key\":\"FLWPUBK_TEST-34d73043494d02b871d7ba051efa471f-X\",\"secret_key\":\"FLWSECK_TEST-efc192c9a48969fc259c517aef8bcc82-X\",\"encryption_key\":\"FLWSECK_TEST4a47bb501118\"}', '0', 1, NULL, '2022-12-16 23:06:06'),
(9, 'global/gateway/coingate.png', 'CoinGate', 'coingate', '[\"BTC\", \"ETH\", \"LTC\", \"XRP\", \"BCH\", \"EOS\", \"XLM\", \"XMR\", \"DASH\", \"DOGE\", \"ZEC\"]', '{\"api_token\":\"NPfn5eAGjha_PqfQmC6F_rMA6_zaGVLmVk6Uvsfu\"}', '0', 1, NULL, '2022-11-22 22:01:12'),
(10, 'global/gateway/monnify.svg', 'Monnify', 'monnify', '[\"NGN\"]', '{\"api_key\":\"MK_TEST_BDQKAE9QNX\",\"api_secret\":\"4PSEUWJJ1HAKEYPXTT375CR9Y1LLX369\",\"base_url\":\"https:\\/\\/sandbox.monnify.com\",\"contract_code\":\"0086188339\"}', '0', 1, NULL, '2022-12-05 09:02:39'),
(11, 'global/gateway/securionpay.png', 'SecurionPay', 'securionpay', '[\"USD\", \"EUR\", \"GBP\", \"JPY\", \"CAD\", \"AUD\", \"CHF\", \"SEK\", \"NOK\", \"DKK\"]', '{\"public_key\":\"pk_test_7bkvbNaJzb1Eh2Egtp9NQ9Os\",\"secret_key\":\"sk_test_7bkvbSEKpHWR4bJL8qdoq7c9\"}', '0', 1, NULL, '2022-12-07 11:11:19'),
(12, 'global/gateway/coinpayments.svg', 'CoinPayments', 'coinpayments', '[\"BTC\", \"BTC.LN\", \"BCH\", \"LTC\", \"VLX\", \"VLX.Native\", \"APL\", \"ASK\", \"BCN\", \"BEAM\", \"BIZZ.TRC20\", \"BNB\", \"BNB.BSC\", \"BTCV\", \"BTG\", \"BTT.OLD\", \"BTT.TRC20\", \"CELO\", \"CLOAK\", \"CRW\", \"CURE\", \"cUSD\", \"USD\", \"CAD\", \"EUR\", \"ARS\", \"AUD\", \"AZN\", \"BGN\", \"BRL\", \"BYN\", \"CHF\", \"CLP\", \"CNY\", \"COP\", \"CZK\",\"DKK\", \"GBP\", \"GIP\", \"HKD\", \"HUF\", \"IDR\", \"ILS\", \"INR\", \"IRR\", \"IRT\", \"ISK\",\"JPY\",\"KRW\",\"LAK\",\"MKD\",\"MXN\",\"ZAR\",\"MYR\", \"NGN\", \"NOK\", \"NZD\", \"PEN\", \"PHP\", \"PKR\", \"PLN\", \"RON\", \"RUB\", \"SEK\", \"SGD\", \"THB\", \"TRY\", \"TWD\", \"UAH\", \"VND\"]', '{\"buyer_email\":\"tdevs330@gmail.com\",\"public_key\":\"07de16d3d2100a2440dc81b8659e1b1e607501ca1a5d57f5dfeeedcee361d7a2\",\"private_key\":\"077D15c2C8F915efbbb054a97A3dcc35a7c994e5F6111deb20f38D3AfF34eD8D\",\"ipn_secret\":\"@tdevs\",\"marchant_id\":\"26dd12941806e2c5e8ecc30460c8b7fc\"}', '0', 1, NULL, '2023-07-08 02:18:04'),
(13, 'global/gateway/nowpayments.png', 'Nowpayments', 'nowpayments', '[\"BTC\", \"ETH\", \"LTC\", \"BCH\", \"BNB\", \"XRP\", \"USDT\", \"DOGE\", \"ADA\", \"DOT\", \"LINK\", \"XLM\", \"USDC\", \"TRX\", \"ATOM\", \"XTZ\", \"EOS\", \"XMR\", \"ZEC\", \"DASH\"]', '{\"api_key\":\"SZTR71W-Z48MZ8G-Q5YH6X5-91F72XK\",\"secret_key\":\"cX7FhXcLYX\\/KJPqFnGgRKbcXDzyVUccN\"}', '0', 1, NULL, '2023-07-07 03:33:31'),
(14, 'global/gateway/coinremitter.png', 'Coinremitter', 'coinremitter', '[\"BTC\",\"ETH\"]', '{\"api_key\":\"$2y$10$mTa7SwWG8k1hJ\\/LP3PLTrOclnliQSrC99fsc528mBeTqBsNlj4MvK\",\"password\":\"12345678\"}', 'to_address', 1, NULL, '2023-07-07 03:33:31'),
(15, 'global/gateway/cryptomus.png', 'Cryptomus', 'cryptomus', '[\"BCH\",\"BNB\",\"BTC\",\"BUSD\",\"CGPT\",\"DAI\",\"DASH\",\"DOFE\",\"ETH\",\"LTC\",\"MATIC\",\"TON\",\"TRX\",\"USDC\",\"USDT\",\"VERSE\",\"XMR\"]\r\n\r\n', '{\"payment_key\":\"uQ4LFWCBE3dT84uQnt7ycL7p9WcSwjkSPQaZbik3ChoWO0egw51f4EAaZQKmefhPP0F1cX8OpRcl2c3HexNedoR7FGEYGA1mTgMPI8lzKl7Ct2I43R6SSC3gVDS3rkGX\",\"payout_key\":\"qseRhcxu6wsxhygfhyidwrrgryrrgefhPP0F1cNedoR7FGEYGA1mTgMPX8OpRcl2c3HexNedoR7FGEYGA1mTgMPI8lzKl7Ct2I43R6S1f4EAaZQKmefhSC3gVDS3rkGX\",\"merchant_id\":\"c26b80a8-9549-4a66-bb53-774f12809249\"}', 'address', 1, NULL, '2023-07-08 10:20:55'),
(16, 'global/gateway/paymongo.png', 'Paymongo', 'paymongo', '[\"PHP\", \"USD\", \"AUD\", \"CAD\", \"EUR\", \"HKD\", \"JPY\", \"SGD\", \"GBP\"]\n', '{\"public_key\":\"pk_test_4dV3PdbbGie3BffowbHVSKCU\",\"secret_key\":\"sk_test_xA6gsPKtgESQY1YMndhufaCo\",\"password\":\"5k0HVZ0^%33n\"}', '0', 1, NULL, '2023-07-11 04:48:12'),
(17, 'global/gateway/btcpayserver.png', 'Btcpayserver', 'btcpayserver', '[\"BTC\", \"ETH\", \"XRP\", \"BCH\", \"LTC\", \"ADA\", \"DOT\", \"LINK\", \"XLM\", \"DOGE\"]', '{\"host\":\"https:\\/\\/btcpay.greenway.management\",\"api_key\":\"94f975f1af98d75df559b9d67e13993929ed2e01\",\"store_id\":\"8kP1mwvGdFpFd55pYkuA8B6fEomnUUvGAa8Ad4n4z91M\",\"webhook_secret\":\"3awb9SbJkqozTkYrfGJ5Bx1R8Xrz\"}', '0', 1, NULL, '2023-07-07 03:33:31');
-- --------------------------------------------------------




INSERT INTO `page_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
   (3, 'username_show', '0', '2023-05-24 17:46:21', '2023-06-11 18:10:27'),
   (4, 'phone_show', '0', '2023-05-24 17:46:21', '2023-06-11 17:58:29'),
   (5, 'country_show', '1', '2023-05-24 17:46:21', '2023-07-21 16:48:54'),
   (6, 'referral_code_show', '1', '2023-05-24 17:46:21', '2023-06-11 18:11:54');


ALTER TABLE `plugins`
    ADD COLUMN `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'system';


INSERT INTO `plugins` (`id`, `icon`, `type`, `name`, `description`, `data`, `status`, `created_at`, `updated_at`) VALUES
(5, 'global/plugin/nexmo.png', 'sms', 'Nexmo', 'Programmatically send and receive high volumes of SMS globally\r\n\r\n\r\n', '{\"from\":\"88029\",\"api_key\":\"d6cc94\",\"api_secret\":\"Gu5QRjOs\"}', 0, NULL, '2023-06-01 18:24:44'),
(6, 'global/plugin/twilio.png', 'sms', 'Twilio', 'Build agility into your customer engagement\r\n\r\n\r\n', '{\"twilio_sid\":\"AC6f1cb19a716d626e\",\"twilio_auth_token\":\"9979a105a89e8a7f0fab49\",\"twilio_phone\":\"+13612821904\"}', 0, NULL, '2023-06-11 12:32:37'),
(7, 'global/plugin/pusher.png', 'notification', 'Pusher', 'Leader In Realtime Technologies\r\n\r\n\r\n', '{\"pusher_app_id\":\"1603962\",\"pusher_app_key\":\"82f66884640\",\"pusher_app_secret\":\"abed21b07f0\",\"pusher_app_cluster\":\"ap2\"}', 0, NULL, '2023-06-11 12:32:52');
--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landing_contents`
--



ALTER TABLE `landing_contents`
    ADD COLUMN `locale_id` int DEFAULT NULL,
    ADD COLUMN `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en';

-- Update the `locale_id` based on the primary key `id`
UPDATE `landing_contents` SET `locale_id` = `id`;

-- --------------------------------------------------------

--
-- Table structure for table `landing_pages`
--

ALTER TABLE `landing_pages`
    ADD COLUMN `locale_id` int DEFAULT NULL,
    ADD COLUMN `short` int DEFAULT NULL,
    ADD COLUMN `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en';

-- Update the `locale_id` based on the primary key `id`
UPDATE `landing_pages`
SET `locale_id` = `id`, `short` = `id`;

-- --------------------------------------------------------

--
-- Table structure for table `login_activities`
--

CREATE TABLE `login_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `navigations`
--

ALTER TABLE `navigations`
    ADD COLUMN `translate` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `for` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `action_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--



ALTER TABLE `pages`
    ADD COLUMN `locale_id` int DEFAULT NULL,
    ADD COLUMN `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en';

-- Update the `locale_id` based on the primary key `id`
UPDATE `pages` SET `locale_id` = `id`;


-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--





-- --------------------------------------------------------

--
-- Table structure for table `push_notification_templates`
--

CREATE TABLE `push_notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `for` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'User',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rankings`
--


ALTER TABLE `rankings`
    ADD COLUMN `minimum_deposit` int DEFAULT NULL,
    ADD COLUMN `minimum_invest` int DEFAULT 0,
    ADD COLUMN `minimum_referral_deposit` int DEFAULT 0,
    ADD COLUMN `minimum_referral_invest` int DEFAULT 0,
    ADD COLUMN `minimum_referral` int DEFAULT 0;



-- --------------------------------------------------------

--
-- Table structure for table `scheduled_task`
--

CREATE TABLE `scheduled_task` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` int NOT NULL COMMENT 'in minutes',
  `scheduled_at` timestamp NOT NULL,
  `scheduled_previous_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schemas`
--




ALTER TABLE `schemas`
    ADD COLUMN `expiry_minute` int DEFAULT 59,
    ADD COLUMN `is_trending` tinyint(1) DEFAULT 0,
    ADD COLUMN `schema_cancel` tinyint(1) DEFAULT 0;


-- --------------------------------------------------------

--
-- Table structure for table `set_tunes`
--

CREATE TABLE `set_tunes` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tune` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_templates`
--

CREATE TABLE `sms_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `for` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'User',
  `message_body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('landing','site') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'landing',
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `themes` (`id`, `name`, `type`, `status`, `created_at`, `updated_at`) VALUES
  (1, 'default', 'site', 1, '2023-07-04 18:47:28', '2023-07-27 01:45:22'),
  (2, 'money_invest', 'site', 0, '2023-07-04 18:47:28', '2023-07-27 01:45:22'),
  (3, 'mining_invest', 'site', 0, '2023-07-04 18:47:28', '2023-07-26 11:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--




ALTER TABLE `gateways`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gateways_gateway_code_unique` (`gateway_code`);



ALTER TABLE `withdraw_methods`
    ADD COLUMN `type` varchar(255) DEFAULT 'manual',
    ADD COLUMN `gateway_id` varchar(255)  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;


--
-- Indexes for table `deposit_methods`
--
ALTER TABLE `deposit_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `login_activities`
--
ALTER TABLE `login_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_notification_templates`
--
ALTER TABLE `push_notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduled_task`
--
ALTER TABLE `scheduled_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `set_tunes`
--
ALTER TABLE `set_tunes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_templates`
--
ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--


--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
    MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

--
-- AUTO_INCREMENT for table `deposit_methods`
--
ALTER TABLE `deposit_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_activities`
--
ALTER TABLE `login_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `push_notification_templates`
--
ALTER TABLE `push_notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled_task`
--
ALTER TABLE `scheduled_task`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `set_tunes`
--
ALTER TABLE `set_tunes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_templates`
--
ALTER TABLE `sms_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `page_settings`
--
ALTER TABLE `page_settings`
    MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

ALTER TABLE `plugins`
    MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;
