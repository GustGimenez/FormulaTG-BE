<?php

require('./vendor/autoload.php');

use FormulaTG\Config\Database\Connection;

$conn = Connection::createConnection();

// $createTableSql = '
//     CREATE TABLE IF NOT EXISTS pilot (
//         id INTEGER PRIMARY KEY,
//         name TEXT,
//         age INTEGER
//     );

//     CREATE TABLE IF NOT EXISTS car (
//         id INTEGER PRIMARY KEY,
//         color TEXT,
//         equip TEXT,
//         pilot_id INTEGER,
//         FOREIGN KEY (pilot_id) REFERENCES pilot (id) 
//     );

//     CREATE TABLE IF NOT EXISTS race_status (
//         id INTEGER PRIMARY KEY,
//         name TEXT
//     );

//     CREATE TABLE IF NOT EXISTS race (
//         id INTEGER PRIMARY KEY,
//         name TEXT,
//         status_id INTEGER,
//         FOREIGN KEY (status_id) REFERENCES race_status (id)
//     );

//     CREATE TABLE IF NOT EXISTS competitor (
//         id INTEGER PRIMARY KEY,
//         position INTEGER,
//         race_id INTEGER,
//         car_id INTEGER,
//         FOREIGN KEY (race_id) REFERENCES race (id),
//         FOREIGN KEY (car_id) REFERENCES car (id)
//     );

//     CREATE TABLE IF NOT EXISTS overtake (
//         id INTEGER PRIMARY KEY,
//         car_overtaking INTEGER,
//         car_overtaken INTEGER,
//         race_id INTEGER,
//         FOREIGN KEY (car_overtaking) REFERENCES car (id),
//         FOREIGN KEY (car_overtaken) REFERENCES car (id),
//         FOREIGN KEY (race_id) REFERENCES race (id)
//     );
// ';
// var_dump($conn->exec($createTableSql));

// $sql = 'DROP TABLE IF EXISTS pilot';
// var_dump($conn->exec($sql));

echo "ta atualizando em" . PHP_EOL;
