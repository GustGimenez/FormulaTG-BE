<?php

namespace FormulaTG\Validators\Command;

use FormulaTG\Exceptions\CommandException;

class ParamsValues extends BaseCommandValidator
{
    public function validate(array $params): void 
    {
        $wrongParams = [];

        foreach ($params as $paramName => $paramValue) {
            if ($paramValue === null || $paramValue === '') {
                $wrongParams[] = $paramName;
            }
        }

        if (!empty($wrongParams)) {
            $imploded = implode(', ', $wrongParams);
            
            throw new CommandException(
                "The following parameters' values are invalid: $imploded"
            );
        }

        parent::validate($params);
    }
}
