<?php

header('Content-Type: text/plain');

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/vendor/autoload.php';

use Tracy\Debugger;
use ParagonIE\EasyDB\Factory;

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

Debugger::enable();

$db = db($_ENV['mysqlHost'], $_ENV['mysqlUser'], $_ENV['mysqlPassword'], $_ENV['mysqlDatabase']);

$db->run("CREATE DATABASE `{$mysqlDatabase}`;");

$sql = <<<SQL
CREATE TABLE `{$mysqlDatabase}`
  (`id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(4000) CHARACTER SET latin1 NOT NULL,
  `host` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE INDEX `idx_added` ON `readinglist` (`added` ASC);
CREATE INDEX `idx_deleted` ON `readinglist` (`deleted` ASC);
SQL;

// Execute query
$db->run($sql);

echo "Table '{$mysqlTable}' created successfully" . PHP_EOL;
