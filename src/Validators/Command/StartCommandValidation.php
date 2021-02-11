<?php

namespace FormulaTG\Validators\Command;

use FormulaTG\Exceptions\CommandException;

class StartCommandValidation implements ValidateCommand
{
    public function validate(array $params): array
    {
        if (count($params) !== 1) {
            throw new CommandException('The "--start" command expects only the race identifier');
        }

        if (!is_numeric($params[0])) {
            throw new CommandException('The argument informed is invalid');
        }

        return $params;
    }
}