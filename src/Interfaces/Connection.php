<?php

namespace App\Interfaces;

use PDO;

interface Connection
{
    public static function connect();
    public static function engine();
}
