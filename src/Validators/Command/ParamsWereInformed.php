<?php

namespace FormulaTG\Validators\Command;

use FormulaTG\Exceptions\CommandException;
use FormulaTG\Utils\Helper;

class ParamsWereInformed extends BaseCommandValidator
{
    public function validate(array $params): void
    {
        foreach ($this->expectedParams as $expectedParam) {
            if (!Helper::containsParam($params, $expectedParam)) {
                throw new CommandException(
                    "Not all parameters informed for the --$this->commandName command");
            }
        }

        parent::validate($params);
    }
}
