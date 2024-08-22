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
                "sqlite:{$_ENV['sqliteDatabase']}",
                $_ENV['mysqlUser'],
                $_ENV['mysqlPassword']
            ]);
        }

        return self::$pdo;
    }
    
    public static function engine(): string
    {
        return 'Sqlite';
    }
}