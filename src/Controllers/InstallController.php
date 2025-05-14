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
            
            $count = $db->cell(
                "SELECT COUNT(*) FROM information_schema.tables 
                WHERE table_schema = '{$_ENV['DB_DATABASE']}' AND table_name = '{$_ENV['DB_TABLE']}'"
            );
            if ($count > 0) {
                throw new \Exception(
                    sprintf('Table "%s" already exists', $_ENV['DB_TABLE'])
                );

                die();
            }
            $db->run("CREATE DATABASE `{$_ENV['DB_DATABASE']}`;");
            
            $sqlmask = include BASE_PATH . '/sql/table.bloglist.mysql.sql';
        } else {

            if (file_exists(BASE_PATH . '/sql/table.bloglist.sqlite.sql')) {
                throw new \Exception(
                    sprintf('Table "%s" already exists', BASE_PATH . '/sql/table.bloglist.sqlite.sql')
                );

                die();
            }
            // NB: SQLite creates the database if required on the connection
            $db = SqliteConnection::connect();
            $sqlmask = include BASE_PATH . '/sql/table.bloglist.sqlite.sql';
        }

        $sql = sprintf($sqlmask, $_ENV['DB_TABLE']);
        $db->run($sql);
        
        echo "Table '{$_ENV['DB_TABLE']}' created successfully" . PHP_EOL;
    }
}
