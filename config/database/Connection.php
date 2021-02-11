<?php

namespace FormulaTG\Config\Database;

use PDO;

class Connection
{
    public static function createConnection(): PDO {
        $path = __DIR__ . '/database.sqlite';
        return new PDO('sqlite:' . $path);
    }
}