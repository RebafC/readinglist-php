<?php

namespace App\Controllers;

use App\MysqlConnection;
use App\SqliteConnection;

class InstallController
{
    private $db;

    private $twigvars = [];

    public function __construct()
    {
    }

    public function index($dbase)
    {
        if (isset($dbase) && strtolower($dbase) !== 'sqlite') {
            $db = MysqlConnection::connect();
            
            $db->run("CREATE DATABASE `{$_ENV['sqliteDatabase']}`;");
            
            $sqlmask = include BASE_PATH . '/sql/table.readinglist.mysql.sql';
        } else {
            // NB: SQLite creates the database if required on the connection
            $db = SqliteConnection::connect();
            $sqlmask = include BASE_PATH . '/sql/table.readinglist.sqlite.sql';
        }

        $sql = sprintf($sqlmask, $_ENV['sqlTable']);
        $db->run($sql);
        
        echo "Table '{$_ENV['sqlTable']}' created successfully" . PHP_EOL;
    }
}
