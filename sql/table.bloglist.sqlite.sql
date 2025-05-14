--
-- Table structure for table `%1$s`
--

CREATE TABLE IF NOT EXISTS `%1$s` (
  `id` integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `host` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `notes` varchar(4000) CHARACTER SET utf8 DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp
) 

CREATE INDEX `idx_added` ON `%1$s` (`added` ASC);
CREATE INDEX `idx_deleted` ON `%1$s` (`deleted` ASC);