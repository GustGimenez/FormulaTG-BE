<?php

namespace FormulaTG\Validators\Command;

use FormulaTG\Exceptions\CommandException;

class CountParams extends BaseCommandValidator
{
    public function validate(array $params): void
    {
        if (count($params) !== count($this->expectedParams)) {
            $countExpectedParams = count($this->expectedParams);

            throw new CommandException("The --$this->commandName command expects " . 
                "exactly $countExpectedParams parameters. " . 
                "Make sure to wrap strings with \"\"");
        }
        
        parent::validate($params);
    }
}
