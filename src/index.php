<?php

require('./vendor/autoload.php');

use FormulaTG\Commands\CommandEntry;
use FormulaTG\Config\Database\DatabaseManager;

// TODO adicionar flag simbolizando se deseja iniciar com banco em branco ou não
// DatabaseManager::initialize();

CommandEntry::commandLoop();

