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
                sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_DATABASE']),
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            ]);
        }

        return self::$pdo;
    }

    public static function engine(): string
    {
        return 'Mysql';
    }
}