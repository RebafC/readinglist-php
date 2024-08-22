<?php

namespace App;

use PDO;
use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory;
use App\Interfaces\Connection;

class MysqlConnection implements Connection
{
    private static EasyDB $pdo;

    public static function connect(): EasyDB
    {
        if (!isset(self::$pdo)) {
            self::$pdo = Factory::fromArray([
                sprintf('mysql:host=%s;dbname=%s', $_ENV['mysqlHost'], $_ENV['mysqlDatabase']),
                $_ENV['mysqlUser'],
                $_ENV['mysqlPassword']
            ]);
        }

        return self::$pdo;
    }

    public static function engine(): string
    {
        return 'Mysql';
    }
}