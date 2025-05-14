<?php

namespace App;

use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory;
use App\Interfaces\Connection;

class SQLiteConnection implements Connection
{
    private static EasyDB $pdo;
    
    public static function connect(): EasyDB
    {
        if (!isset(self::$pdo)) {
            self::$pdo = Factory::fromArray([
                "sqlite:{$_ENV['DB_DATABASE']}",
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            ]);
        }

        return self::$pdo;
    }
    
    public static function engine(): string
    {
        return 'Sqlite';
    }
}