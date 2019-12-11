-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Vært: 127.0.0.1
-- Genereringstid: 11. 12 2019 kl. 14:53:09
-- Serverversion: 10.1.21-MariaDB
-- PHP-version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cph_game_db`
--
CREATE DATABASE IF NOT EXISTS `cph_game_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cph_game_db`;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_entrant`
--

CREATE TABLE `tbl_entrant` (
  `fk_entrant_game` int(11) NOT NULL,
  `fk_entrant_user` int(11) NOT NULL,
  `fk_entrant_result` int(11) DEFAULT NULL,
  `entrant_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_game`
--

CREATE TABLE `tbl_game` (
  `game_id` int(11) NOT NULL,
  `game_name` varchar(64) NOT NULL,
  `game_description` text NOT NULL,
  `game_url` varchar(256) NOT NULL,
  `game_level` varchar(24) NOT NULL,
  `fk_game_media` int(11) DEFAULT NULL,
  `fk_game_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_game`
--

INSERT INTO `tbl_game` (`game_id`, `game_name`, `game_description`, `game_url`, `game_level`, `fk_game_media`, `fk_game_type`) VALUES
(1, 'A-MAZE-ING (The Game)', '&#60;p&#62;Et helt fantastisk labyrint spil!&#60;/p&#62;&#13;&#10;', 'plugin\\maze\\index.html', '1', 112, 1),
(2, 'JS-game-maze', '&#60;p&#62;Sif&#38;#39;s labyrint game&#60;/p&#62;&#13;&#10;', 'https://sifaa.github.io/js-game-maze/', '2', 113, 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_media`
--

CREATE TABLE `tbl_media` (
  `media_id` int(11) NOT NULL,
  `media_path` varchar(128) NOT NULL,
  `media_small` varchar(128) DEFAULT NULL,
  `media_medium` varchar(128) DEFAULT NULL,
  `media_type` varchar(15) NOT NULL,
  `fk_media_news` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_media`
--

INSERT INTO `tbl_media` (`media_id`, `media_path`, `media_small`, `media_medium`, `media_type`, `fk_media_news`) VALUES
(112, 'games/1575555857_JS-GAME.png', NULL, NULL, 'image/png', NULL),
(113, 'games/1575976851_screenshot-sifaa.github.io-2019.12.10-12_20_17.png', NULL, NULL, 'image/png', NULL),
(157, 'user_img/1576068062_images.jpg', NULL, NULL, 'image/jpeg', NULL),
(158, 'user_img/1576068325_hans.jpg', NULL, NULL, 'image/jpeg', NULL),
(159, 'user_img/1576068897_ib.jpg', NULL, NULL, 'image/jpeg', NULL),
(160, '1576070511_1495545010_m_3.jpg', 'small/small_1576070511_1495545010_m_3.jpg', 'medium/medium_1576070511_1495545010_m_3.jpg', 'image/jpeg', 58);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_message`
--

CREATE TABLE `tbl_message` (
  `message_id` int(11) NOT NULL,
  `message_name` varchar(128) NOT NULL,
  `message_email` varchar(128) NOT NULL,
  `message_content` text NOT NULL,
  `message_phone` varchar(25) DEFAULT NULL,
  `message_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_message`
--

INSERT INTO `tbl_message` (`message_id`, `message_name`, `message_email`, `message_content`, `message_phone`, `message_created`) VALUES
(10, 'Ole Bole', 'ole@bole.dk', 'Man kan fremad se, at de har været udset til at læse, at der skal dannes par af ligheder. Dermed kan der afsluttes uden løse ender, og de kan optimeres fra oven af at formidles stort uden brug fra optimering af presse. I en kant af landet går der blandt om, at de vil sætte den over forbehold for tiden. ', '40292221', '2019-12-11 14:14:59');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_news`
--

CREATE TABLE `tbl_news` (
  `news_id` int(11) NOT NULL,
  `news_title` varchar(64) NOT NULL,
  `news_content` text NOT NULL,
  `news_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_news`
--

INSERT INTO `tbl_news` (`news_id`, `news_title`, `news_content`, `news_created`) VALUES
(58, '1. Nyhed', 'Man kan fremad se, at de har været udset til at læse, at der skal dannes par af ligheder. Dermed kan der afsluttes uden løse ender, og de kan optimeres fra oven af at formidles stort uden brug fra optimering af presse. I en kant af landet går der blandt om, at de vil sætte den over forbehold for tiden. Vi flotter med et hold, der vil rundt og se sig om i byen. Det gør heller ikke mere. Men hvor vi nu overbringer denne størrelse til det søgeoptimering handler om, så kan der fortælles op til 3 gange. Hvis det er træet til dit bord der får dig op, er det snarere varmen over de andre. Selv om hun har sat alt mere frem, og derfor ikke længere kan betragtes som den glade giver, er det en nem sammenstilling, som bærer ved i lang tid. Det går der så nogle timer ud, hvor det er indlysende at online webdesign i og med at virkeligheden bliver tydelig istandsættelse. Det er opmuntrende og anderledes, at det er dampet af kurset i morgen. Der indgives hvert år enorme strenge af blade af større eller mindre tilsnit.', '2019-12-11 14:21:51');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_newsletter`
--

CREATE TABLE `tbl_newsletter` (
  `newsletter_id` int(11) NOT NULL,
  `newsletter_email` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_newsletter`
--

INSERT INTO `tbl_newsletter` (`newsletter_id`, `newsletter_email`) VALUES
(1, 'admin@mail.dk');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_profile`
--

CREATE TABLE `tbl_profile` (
  `profile_id` int(11) NOT NULL,
  `profile_firstname` varchar(128) NOT NULL,
  `profile_sirname` varchar(128) NOT NULL,
  `profile_username` varchar(64) NOT NULL,
  `profile_age` varchar(12) NOT NULL,
  `profile created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_profile_rank` int(11) DEFAULT NULL,
  `fk_profile_media` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_profile`
--

INSERT INTO `tbl_profile` (`profile_id`, `profile_firstname`, `profile_sirname`, `profile_username`, `profile_age`, `profile created`, `profile_updated`, `fk_profile_rank`, `fk_profile_media`) VALUES
(32, 'Ole', 'Bole', 'OHIGH', '04-12-1970', '2019-12-11 13:41:02', '2019-12-11 13:41:02', NULL, 157),
(33, 'Anders', 'And', 'Donald', '2017-01-10', '2019-12-11 13:45:25', '2019-12-11 13:45:25', NULL, 158),
(34, 'Micky', 'Mouse', 'Micky M', '2001-02-14', '2019-12-11 13:54:57', '2019-12-11 13:54:57', NULL, 159);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_reset`
--

CREATE TABLE `tbl_reset` (
  `reset_id` int(11) NOT NULL,
  `reset_expire` varchar(10) NOT NULL,
  `reset_user` int(11) NOT NULL,
  `reset_encrypt` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_reset`
--

INSERT INTO `tbl_reset` (`reset_id`, `reset_expire`, `reset_user`, `reset_encrypt`) VALUES
(5, '1511423332', 5, 'a9a1d5317a33ae8cef33961c34144f84'),
(6, '1511437709', 1, '069059b7ef840f0c74a814ec9237b6ec'),
(7, '1511437828', 1, '1013c8b99e603831ad123eab4b27660f'),
(8, '1521712240', 6, 'a96d3afec184766bfeca7a9f989fc7e7'),
(9, '1521712827', 3, 'c705112d1ec18b97acac7e2d63973424'),
(10, '1521713773', 6, 'e5ba7c3bbe8402a49a10fed2162dac54'),
(11, '1522159881', 5, '7884a9652e94555c70f96b6be63be216'),
(12, '1522166901', 4, '68ce199ec2c5517597ce0a4d89620f55'),
(13, '1522174867', 5, 'adbe673fd502b32bee221970f9cb0e8d'),
(14, '1522400375', 5, 'c5c53759e4dd1bfe8b3dcfec37d0ea72'),
(15, '1575455291', 3, '363ce3cd61389226b4a55b2aee2dacd7');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_result`
--

CREATE TABLE `tbl_result` (
  `result_id` int(11) NOT NULL,
  `result_point` int(11) NOT NULL,
  `result_time` int(11) NOT NULL,
  `result_score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_role`
--

CREATE TABLE `tbl_role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(30) NOT NULL,
  `role_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_role`
--

INSERT INTO `tbl_role` (`role_id`, `role_name`, `role_level`) VALUES
(1, 'Administrator', 99),
(2, 'Moderator', 60),
(3, 'Medlem', 30);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_type`
--

CREATE TABLE `tbl_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_type`
--

INSERT INTO `tbl_type` (`type_id`, `type_name`) VALUES
(1, 'Labyrint'),
(2, 'Puzzel'),
(3, 'Action');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(128) NOT NULL,
  `user_password` varchar(70) NOT NULL,
  `user_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_user_profile` int(11) DEFAULT NULL,
  `fk_user_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_email`, `user_password`, `user_created`, `user_updated`, `fk_user_profile`, `fk_user_role`) VALUES
(26, 'admin@mail.dk', '$2y$10$G3ymXNzBBmxpeY3DEyclWuNVV2do8yO0nUHbC4YARZeJCqIBAcnmq', '2019-12-11 13:41:02', '2019-12-11 13:42:25', 32, 1),
(27, 'anders@mail.dk', '$2y$10$v8O6QCP.3t90Ey3EkXggkOb.cQoUXPALBAU.TstdISOS.DDDy95S6', '2019-12-11 13:45:25', '2019-12-11 13:59:39', 33, 3),
(28, 'micky@mail.dk', '$2y$10$2S68gz1MkxSqlKOki1XVMuKLE/L6xbUWZA6GY/uViTGHDicAnBoVS', '2019-12-11 13:54:57', '2019-12-11 13:59:48', 34, 2);

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `tbl_entrant`
--
ALTER TABLE `tbl_entrant`
  ADD KEY `fk_entrant_event` (`fk_entrant_game`,`fk_entrant_user`),
  ADD KEY `fk_entrant_user` (`fk_entrant_user`);

--
-- Indeks for tabel `tbl_game`
--
ALTER TABLE `tbl_game`
  ADD PRIMARY KEY (`game_id`);

--
-- Indeks for tabel `tbl_media`
--
ALTER TABLE `tbl_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `fk_media_event` (`fk_media_news`);

--
-- Indeks for tabel `tbl_message`
--
ALTER TABLE `tbl_message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indeks for tabel `tbl_news`
--
ALTER TABLE `tbl_news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indeks for tabel `tbl_newsletter`
--
ALTER TABLE `tbl_newsletter`
  ADD PRIMARY KEY (`newsletter_id`);

--
-- Indeks for tabel `tbl_profile`
--
ALTER TABLE `tbl_profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `fk_profile_media` (`fk_profile_media`);

--
-- Indeks for tabel `tbl_reset`
--
ALTER TABLE `tbl_reset`
  ADD PRIMARY KEY (`reset_id`);

--
-- Indeks for tabel `tbl_result`
--
ALTER TABLE `tbl_result`
  ADD PRIMARY KEY (`result_id`);

--
-- Indeks for tabel `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indeks for tabel `tbl_type`
--
ALTER TABLE `tbl_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indeks for tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_user_profile` (`fk_user_profile`,`fk_user_role`),
  ADD KEY `fk_user_role` (`fk_user_role`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `tbl_game`
--
ALTER TABLE `tbl_game`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_media`
--
ALTER TABLE `tbl_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_message`
--
ALTER TABLE `tbl_message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_news`
--
ALTER TABLE `tbl_news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_newsletter`
--
ALTER TABLE `tbl_newsletter`
  MODIFY `newsletter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_profile`
--
ALTER TABLE `tbl_profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_reset`
--
ALTER TABLE `tbl_reset`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_result`
--
ALTER TABLE `tbl_result`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_type`
--
ALTER TABLE `tbl_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Tilføj AUTO_INCREMENT i tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
