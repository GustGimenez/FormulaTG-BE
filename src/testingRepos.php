<?php

require('./vendor/autoload.php');

use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\Car;
use FormulaTG\Models\Race;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\CarRepository;
use FormulaTG\Repositories\CompetitorRepository;
use FormulaTG\Repositories\GenericRepository;
use FormulaTG\Repositories\RaceRepository;

$conn = Connection::createConnection();
$repository = new RaceRepository($conn);

// $race = new Race('GP test');

// var_dump($repository->insert($race, 'race', $race->getTableColumns(), $race->getInsertValues()));
// var_dump($race);


var_dump($repository->changeStatus(1, RaceStatus::STARTED));
