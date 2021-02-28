<?php

require('./vendor/autoload.php');

use FormulaTG\Commands\CommandEntry;
use FormulaTG\Config\Database\DatabaseManager;

DatabaseManager::initialize();

CommandEntry::commandLoop();

