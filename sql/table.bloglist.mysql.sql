--
-- Table structure for table `%1$s`
--

CREATE TABLE IF NOT EXISTS `%1$s` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `host` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `notes` varchar(4000) CHARACTER SET utf8 DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE INDEX `idx_added` ON `%1$s` (`added` ASC);
CREATE INDEX `idx_deleted` ON `%1$s` (`deleted` ASC);