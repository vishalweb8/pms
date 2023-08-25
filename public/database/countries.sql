-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 17, 2021 at 02:53 PM
-- Server version: 5.7.35-0ubuntu0.18.04.1
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Dumping data for table `countries`
--
TRUNCATE `countries`;

INSERT INTO `countries` (`id`, `name`, `iso`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Andorra', 'AD', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(2, 'United Arab Emirates', 'AE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(3, 'Afghanistan', 'AF', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(4, 'Antigua And Barbuda', 'AG', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(5, 'Anguilla', 'AI', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(6, 'Albania', 'AL', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(7, 'Armenia', 'AM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(8, 'Angola', 'AO', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(9, 'Argentina', 'AR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(10, 'American Samoa', 'AS', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(11, 'Austria', 'AT', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(12, 'Australia', 'AU', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(13, 'Aruba', 'AW', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(14, 'Aland Islands', 'AX', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(15, 'Azerbaijan', 'AZ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(16, 'Bosnia And Herzegovina', 'BA', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(17, 'Barbados', 'BB', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(18, 'Bangladesh', 'BD', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(19, 'Belgium', 'BE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(20, 'Burkina Faso', 'BF', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(21, 'Bulgaria', 'BG', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(22, 'Bahrain', 'BH', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(23, 'Burundi', 'BI', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(24, 'Benin', 'BJ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(25, 'Saint Barthelemy', 'BL', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(26, 'Bermuda', 'BM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(27, 'Brunei', 'BN', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(28, 'Bolivia', 'BO', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(29, 'Bonaire, Saint Eustatius And Saba ', 'BQ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(30, 'Brazil', 'BR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(31, 'Bahamas', 'BS', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(32, 'Bhutan', 'BT', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(33, 'Botswana', 'BW', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(34, 'Belarus', 'BY', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(35, 'Belize', 'BZ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(36, 'Canada', 'CA', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(37, 'Cocos Islands', 'CC', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(38, 'Democratic Republic Of The Congo', 'CD', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(39, 'Central African Republic', 'CF', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(40, 'Republic Of The Congo', 'CG', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(41, 'Switzerland', 'CH', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(42, 'Ivory Coast', 'CI', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(43, 'Cook Islands', 'CK', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(44, 'Chile', 'CL', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(45, 'Cameroon', 'CM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(46, 'China', 'CN', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(47, 'Colombia', 'CO', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(48, 'Costa Rica', 'CR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(49, 'Cuba', 'CU', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(50, 'Cabo Verde', 'CV', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(51, 'Curacao', 'CW', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(52, 'Cyprus', 'CY', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(53, 'Czechia', 'CZ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(54, 'Germany', 'DE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(55, 'Djibouti', 'DJ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(56, 'Denmark', 'DK', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(57, 'Dominica', 'DM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(58, 'Dominican Republic', 'DO', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(59, 'Algeria', 'DZ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(60, 'Ecuador', 'EC', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(61, 'Estonia', 'EE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(62, 'Egypt', 'EG', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(63, 'Western Sahara', 'EH', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(64, 'Eritrea', 'ER', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(65, 'Spain', 'ES', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(66, 'Ethiopia', 'ET', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(67, 'Finland', 'FI', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(68, 'Fiji', 'FJ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(69, 'Falkland Islands', 'FK', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(70, 'Micronesia', 'FM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(71, 'Faroe Islands', 'FO', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(72, 'France', 'FR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(73, 'Gabon', 'GA', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(74, 'United Kingdom', 'GB', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(75, 'Grenada', 'GD', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(76, 'Georgia', 'GE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(77, 'French Guiana', 'GF', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(78, 'Guernsey', 'GG', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(79, 'Ghana', 'GH', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(80, 'Gibraltar', 'GI', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(81, 'Greenland', 'GL', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(82, 'Gambia', 'GM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(83, 'Guinea', 'GN', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(84, 'Guadeloupe', 'GP', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(85, 'Equatorial Guinea', 'GQ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(86, 'Greece', 'GR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(87, 'South Georgia And The South Sandwich Islands', 'GS', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(88, 'Guatemala', 'GT', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(89, 'Guam', 'GU', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(90, 'Guinea-Bissau', 'GW', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(91, 'Guyana', 'GY', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(92, 'Hong Kong', 'HK', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(93, 'Honduras', 'HN', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(94, 'Croatia', 'HR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(95, 'Haiti', 'HT', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(96, 'Hungary', 'HU', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(97, 'Indonesia', 'ID', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(98, 'Ireland', 'IE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(99, 'Israel', 'IL', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(100, 'Isle Of Man', 'IM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(101, 'India', 'IN', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(102, 'Iraq', 'IQ', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(103, 'Iran', 'IR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(104, 'Iceland', 'IS', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(105, 'Italy', 'IT', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(106, 'Jersey', 'JE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(107, 'Jamaica', 'JM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(108, 'Jordan', 'JO', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(109, 'Japan', 'JP', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(110, 'Kenya', 'KE', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(111, 'Kyrgyzstan', 'KG', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(112, 'Cambodia', 'KH', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(113, 'Kiribati', 'KI', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(114, 'Comoros', 'KM', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(115, 'Saint Kitts And Nevis', 'KN', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(116, 'North Korea', 'KP', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(117, 'South Korea', 'KR', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(118, 'Kosovo', 'XK', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:12'),
(119, 'Kuwait', 'KW', 1, '2020-02-11 06:41:12', '2020-02-11 06:41:13'),
(120, 'Cayman Islands', 'KY', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(121, 'Kazakhstan', 'KZ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(122, 'Laos', 'LA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(123, 'Lebanon', 'LB', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(124, 'Saint Lucia', 'LC', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(125, 'Liechtenstein', 'LI', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(126, 'Sri Lanka', 'LK', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(127, 'Liberia', 'LR', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(128, 'Lesotho', 'LS', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(129, 'Lithuania', 'LT', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(130, 'Luxembourg', 'LU', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(131, 'Latvia', 'LV', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(132, 'Libya', 'LY', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(133, 'Morocco', 'MA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(134, 'Monaco', 'MC', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(135, 'Moldova', 'MD', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(136, 'Montenegro', 'ME', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(137, 'Saint Martin', 'MF', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(138, 'Madagascar', 'MG', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(139, 'Marshall Islands', 'MH', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(140, 'North Macedonia', 'MK', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(141, 'Mali', 'ML', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(142, 'Myanmar', 'MM', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(143, 'Mongolia', 'MN', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(144, 'Macao', 'MO', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(145, 'Northern Mariana Islands', 'MP', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(146, 'Martinique', 'MQ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(147, 'Mauritania', 'MR', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(148, 'Montserrat', 'MS', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(149, 'Malta', 'MT', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(150, 'Mauritius', 'MU', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(151, 'Maldives', 'MV', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(152, 'Malawi', 'MW', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(153, 'Mexico', 'MX', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(154, 'Malaysia', 'MY', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(155, 'Mozambique', 'MZ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(156, 'Namibia', 'NA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(157, 'New Caledonia', 'NC', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(158, 'Niger', 'NE', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(159, 'Norfolk Island', 'NF', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(160, 'Nigeria', 'NG', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(161, 'Nicaragua', 'NI', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(162, 'Netherlands', 'NL', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(163, 'Norway', 'NO', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(164, 'Nepal', 'NP', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(165, 'Nauru', 'NR', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(166, 'Niue', 'NU', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(167, 'New Zealand', 'NZ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(168, 'Oman', 'OM', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(169, 'Panama', 'PA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(170, 'Peru', 'PE', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(171, 'French Polynesia', 'PF', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(172, 'Papua New Guinea', 'PG', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(173, 'Philippines', 'PH', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(174, 'Pakistan', 'PK', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(175, 'Poland', 'PL', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(176, 'Saint Pierre And Miquelon', 'PM', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(177, 'Puerto Rico', 'PR', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(178, 'Palestinian Territory', 'PS', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(179, 'Portugal', 'PT', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(180, 'Palau', 'PW', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(181, 'Paraguay', 'PY', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(182, 'Qatar', 'QA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(183, 'Reunion', 'RE', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(184, 'Romania', 'RO', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(185, 'Serbia', 'RS', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(186, 'Russia', 'RU', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(187, 'Rwanda', 'RW', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(188, 'Saudi Arabia', 'SA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(189, 'Solomon Islands', 'SB', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(190, 'Seychelles', 'SC', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(191, 'Sudan', 'SD', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(192, 'South Sudan', 'SS', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(193, 'Sweden', 'SE', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(194, 'Saint Helena', 'SH', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(195, 'Slovenia', 'SI', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(196, 'Svalbard And Jan Mayen', 'SJ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(197, 'Slovakia', 'SK', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(198, 'Sierra Leone', 'SL', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(199, 'San Marino', 'SM', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(200, 'Senegal', 'SN', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(201, 'Somalia', 'SO', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(202, 'Suriname', 'SR', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(203, 'Sao Tome And Principe', 'ST', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(204, 'El Salvador', 'SV', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(205, 'Sint Maarten', 'SX', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(206, 'Syria', 'SY', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(207, 'Eswatini', 'SZ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(208, 'Turks And Caicos Islands', 'TC', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(209, 'Chad', 'TD', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(210, 'French Southern Territories', 'TF', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(211, 'Togo', 'TG', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(212, 'Thailand', 'TH', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(213, 'Tajikistan', 'TJ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(214, 'Tokelau', 'TK', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(215, 'Timor Leste', 'TL', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(216, 'Turkmenistan', 'TM', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(217, 'Tunisia', 'TN', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(218, 'Tonga', 'TO', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(219, 'Turkey', 'TR', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(220, 'Trinidad And Tobago', 'TT', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(221, 'Tuvalu', 'TV', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(222, 'Taiwan', 'TW', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(223, 'Tanzania', 'TZ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(224, 'Ukraine', 'UA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(225, 'Uganda', 'UG', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(226, 'United States Minor Outlying Islands', 'UM', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(227, 'United States', 'US', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(228, 'Uruguay', 'UY', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(229, 'Uzbekistan', 'UZ', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(230, 'Saint Vincent And The Grenadines', 'VC', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(231, 'Venezuela', 'VE', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(232, 'British Virgin Islands', 'VG', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(233, 'U.S. Virgin Islands', 'VI', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(234, 'Vietnam', 'VN', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(235, 'Vanuatu', 'VU', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(236, 'Wallis And Futuna', 'WF', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(237, 'Samoa', 'WS', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(238, 'Yemen', 'YE', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(239, 'Mayotte', 'YT', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(240, 'South Africa', 'ZA', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(241, 'Zambia', 'ZM', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13'),
(242, 'Zimbabwe', 'ZW', 1, '2020-02-11 06:41:13', '2020-02-11 06:41:13');

--
-- Indexes for dumped tables
--
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
