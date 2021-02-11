<?php

require('./vendor/autoload.php');

/*
Possíveis comandos:
    --help: exibe todas as possibilidades de comandos
    --help <comando>: exibe todos parâmetros para o comando
    --list <entidade>: exibe uma lista das entidades do banco para que o usuário possa se orientar
    --create: criar um Car ou Race
    --start: inicializa uma corrida
    --finish: finaliza uma corrida (confirmar pra verificar se precisa ter voltas na corrida)
    --overtake <color/name> <color/name>: realiza uma ultrapassagem na corrida em andamento 
*/


/*
TODO:
    - modularizar métodos dos command validators
    - criar class pai para validators
*/

use FormulaTG\Commands\ListCommand;
use FormulaTG\Commands\CreateCommand;
use FormulaTG\Commands\FinishCommand;
use FormulaTG\Commands\OvertakeCommand;
use FormulaTG\Commands\OverviewCommand;
use FormulaTG\Commands\PositionCommand;
use FormulaTG\Commands\StartCommand;

entryComand($argv);

function entryComand(array $params) 
{
    if (count($params) === 1) {
        echo 'For help, use the "--help" command';
    }

    identifyCommand($params);
}

function removeUnwantedParams(array $params, string $commandName): array
{
    $fileIndex = array_search('./FormulaTG.php', $params);
    $commandIndex = array_search($commandName, $params);

    unset($params[$fileIndex]);
    unset($params[$commandIndex]);

    return array_values($params);
}

function identifyCommand(array $params): void
{
    if (in_array('--list', $params)) {
        $params = removeUnwantedParams($params, '--list');

        $command = new ListCommand($params);
        echo $command->execute();
    }

    if (in_array('--create', $params)) {
        $params = removeUnwantedParams($params, '--create');

        $command = new CreateCommand($params);
        echo $command->execute();
    }

    if (in_array('--start', $params)) {
        $params = removeUnwantedParams($params, '--start');

        $command = new StartCommand($params);
        echo $command->execute();
    }

    if (in_array('--positions', $params)) {
        $params = removeUnwantedParams($params, '--positions');

        $command = new PositionCommand($params);
        echo $command->execute();
    }

    if (in_array('--overtake', $params)) {
        $params = removeUnwantedParams($params, '--overtake');

        $command = new OvertakeCommand($params);
        echo $command->execute();
    }

    if (in_array('--overview', $params)) {
        $params = removeUnwantedParams($params, '--overview');

        $command = new OverviewCommand($params);
        echo $command->execute();
    }

    if (in_array('--finish', $params)) {
        $params = removeUnwantedParams($params, '--finish');

        $command = new FinishCommand($params);
        echo $command->execute();
    }
}
