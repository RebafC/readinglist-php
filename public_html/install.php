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
    
    $sqlmask = include BASE_PATH . '/sql/table.bloglist.mysql.sql';
} else {
    $db = SqliteConnection::connect();
    $sqlmask = include BASE_PATH . '/sql/table.bloglist.sqlite.sql';
}

$sql = sprintf($sqlmask, $_ENV['DB_TABLE']);
$db->run($sql);

echo "Table '{$_ENV['DB_TABLE']}' created successfully" . PHP_EOL;
