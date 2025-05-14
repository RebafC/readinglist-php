<?php

namespace App\Traits;

use App\MysqlConnection;
use App\SqliteConnection;
use ParagonIE\EasyDB\EasyDB;

trait GetConnection
{
    public function getDB(string $dbase): EasyDB
    {
        return (strtolower($dbase) !== 'sqlite')
            ? MysqlConnection::connect()
            : SqliteConnection::connect();
    }
}
