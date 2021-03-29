<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Exceptions\CommandException;
use ReflectionClass;

class CommandEntry {
    private const COMMANDS = [
        '--list' => ListCommand::class,
        '--create' => CreateCommand::class,
        '--finish' => FinishCommand::class,
        '--overtake' => OvertakeCommand::class,
        '--overview' => OverviewCommand::class,
        '--positions' => PositionCommand::class,
        '--start' => StartCommand::class,
        '--history' => HistoryCommand::class,
    ];

    public static function commandLoop(): void
    {
        echo '----- Welcome to Gutavo\'s Formula TG -----' . PHP_EOL . PHP_EOL;

        $stopExecution = false;
        do {
            echo  PHP_EOL . 'Waiting for the next command... (To stop execution type "stop"):' . PHP_EOL;

            try {
                echo self::identifyCommand();
            } catch (Exception $e) {
                $e->getMessage();
            }
        } while (!$stopExecution);
    }

    private static function removeUnwantedParams(array $params, string $commandName): array
    {
        $commandIndex = array_search($commandName, $params);

        unset($params[$commandIndex]);

        return array_values($params);
    }

    private static function checkForExitCommand(string $command): void
    {
        if (gettype($command) !== 'string') {
            return;
        }

        if ($command === 'stop') {
            echo 'Stopping application...' . PHP_EOL;
            die();
        }
    }

    private static function getEveryPositionOfSubString(string $needle, string $text): array
    {
        $lastPostion = 0;
        $positions = [];

        while (($lastPostion = strpos($text, $needle, $lastPostion)) !== false) {
            $positions[] = $lastPostion;
            $lastPostion += strlen($needle);
        }

        return $positions;
    }

    private static function checkIfParamIsString(int $fromPosition, string $command): bool
    {
        return $command[$fromPosition + 1] === '"';
    }

    private static function getStringValue(string $command, int $position): string
    {
        $stringOpenning = $position + 1;
        $stringClosure = strpos($command, '"', $stringOpenning + 1);

        if ($stringClosure === false) {
            throw new CommandException('String malformation detected. Please check the command typed');
        }
        
        return substr($command, $stringOpenning, $stringClosure - $position);
    }

    private static function restoreParams(array $params, array $backup): array
    {
        foreach ($backup as $backupParam) {
            foreach ($params as $key => $param) {
                if (strpos($param, $backupParam['preparedValue']) !== false) {
                    $params[$key] = str_replace(
                        $backupParam['preparedValue'],
                        $backupParam['originalValue'],
                        $param,
                    );
                }
            }
        }

        return $params;
    }

    private static function prepareParams(string $command): array
    {
        $positions = self::getEveryPositionOfSubString('=', $command);
        $backup = [];

        foreach ($positions as $position) {
            if (self::checkIfParamIsString($position, $command)) {
                $stringValue = self::getStringValue($command, $position);
                $preparedValue = str_replace(' ', '_', $stringValue);
                $command = str_replace($stringValue, $preparedValue, $command);

                $backup[] = [
                    'originalValue' => str_replace('"', '', $stringValue),
                    'preparedValue' => $preparedValue,
                ];
            }
        }

        $params = explode(' ', $command);

        return self::restoreParams($params, $backup);
    }

    private static function identifyCommand(): string
    {
        $handle = fopen ('php://stdin', 'r');
        $command = trim(fgets($handle));

        self::checkForExitCommand($command);

        $params = self::prepareParams($command);

        foreach (self::COMMANDS as $command => $className) {
            if (in_array($command, $params)) {
                $params = self::removeUnwantedParams($params, $command);

                $reflectionClass = new ReflectionClass($className);
                $command = $reflectionClass->newInstanceArgs(['params' => $params]);
                return $command->execute();
            }
        }
    
        return 'Unidentified command' . PHP_EOL;
    }
}
