<?php

namespace FormulaTG\Validators\Command;

use FormulaTG\Exceptions\CommandException;
use FormulaTG\Utils\Helper;

class PositionCommandValidation implements ValidateCommand
{
    public function validate(array $params): array
    {
        $params = Helper::formParams($params);
        if (count($params) < 2) {
            throw new CommandException('The --postion command expects two parameters');
        }

        $this->validateIfParamsWereInformed($params, ['race', 'cars']);
        $this->validateParamsValues($params);

        return $params;
    }

    private function validateIfParamsWereInformed(array $params, array $expectedParams): void 
    {    
        foreach ($expectedParams as $expectedParam) {
            if (!Helper::containsParam($params, $expectedParam)) {
                throw new CommandException("Not all parameters informed for the --positions command");
            }
        }
    }

    private function validateParamsValues(array $params): void 
    {
        foreach ($params as $paramName => $paramValue) {
            if ($paramValue === null || $paramValue === '') {
                throw new CommandException("The value of '$paramName' is invalid");
            }
        }
    }
}