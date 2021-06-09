<?php


namespace FormulaTG\Config\Database;


class DatabaseManager
{
    public static function initialize(): void
    {
        $conn = Connection::createConnection();

        $sql = 'DELETE FROM overtake';
        $conn->exec($sql);

        $sql = 'DELETE FROM competitor';
        $conn->exec($sql);

        $sql = 'DELETE FROM race';
        $conn->exec($sql);

        $sql = 'DELETE FROM car';
        $conn->exec($sql);

        $sql = 'DELETE FROM pilot';
        $conn->exec($sql);
    }
}