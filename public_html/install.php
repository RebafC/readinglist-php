<?php


define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/vendor/autoload.php';

use Tracy\Debugger;
use App\MysqlConnection;
use App\SqliteConnection;

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

Debugger::enable();

if (isset($_GET['dbase']) && $_GET['dbase'] !== 'sqlite') {
    $db = MysqlConnection::connect();
    
    $db->run("CREATE DATABASE `{$_ENV['sqliteDatabase']}`;");
    
    $sql = <<<SQL
CREATE TABLE `{$_ENV['sqlTable']}`
  (`id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(4000) CHARACTER SET latin1 NOT NULL,
  `host` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE INDEX `idx_added` ON `{$_ENV['sqlTable']}` (`added_at` ASC);
CREATE INDEX `idx_deleted` ON `{$_ENV['sqlTable']}` (`deleted_at` ASC);
SQL;
} else {
    $db = SqliteConnection::connect();
    
    $sql = <<<SQL
CREATE TABLE `{$_ENV['sqlTable']}`
  (`id` integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(4000) NOT NULL,
  `host` varchar(255) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp
);
CREATE INDEX `idx_added` ON `{$_ENV['sqlTable']}` (`added_at` ASC);
CREATE INDEX `idx_deleted` ON `{$_ENV['sqlTable']}` (`deleted_at` ASC);
SQL;
}
$db->run($sql);

echo "Table '{$_ENV['sqlTable']}' created successfully" . PHP_EOL;
