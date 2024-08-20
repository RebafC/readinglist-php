--
-- Table structure for table `readinglist`
--

CREATE TABLE IF NOT EXISTS `readinglist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(4000) CHARACTER SET latin1 NOT NULL,
  `host` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE INDEX `idx_added` ON `readinglist` (`added` ASC);
CREATE INDEX `idx_deleted` ON `readinglist` (`deleted` ASC);