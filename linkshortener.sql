CREATE TABLE `shortedlinks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `links` varchar(255) NOT NULL,
  `shortedid` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;