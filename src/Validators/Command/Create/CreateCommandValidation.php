<?php

namespace FormulaTG\Validators\Command\Create;

use FormulaTG\Exceptions\CommandException;
use FormulaTG\Utils\Helper;
use FormulaTG\Validators\Command\ValidateCommand;

class CreateCommandValidation implements ValidateCommand
{
    protected array $expectedParams;
    protected string $entity;

    public function __construct(array $expectedParams, string $entity)
    {
        $this->expectedParams = $expectedParams;
        $this->entity = $entity;
    }

    public function validate(array $params): array
    {
        $params = $this->removeEntityFromParams($params);
        $params = Helper::formParams(array_values($params));

        if (count($params) !== count($this->expectedParams)) {
            $countExpectedParams = count($this->expectedParams);
            throw new CommandException("The --create $this->entity command expects " . 
            "exactly $countExpectedParams parameters. " . 
            "Make sure to wrap strings with \"\"");
        }
        
        $this->validateIfParamsWereInformed($params, $this->expectedParams);
        $this->validateParamsValues($params);

        return $params;
    }

    private function removeEntityFromParams(array $params): array
    {
        $entityIndex = array_search('car', $params);
        unset($params[$entityIndex]);
        return array_values($params);
    }

    private function validateParamsValues(array $params): void 
    {
        foreach ($params as $paramName => $paramValue) {
            if ($paramValue === null || $paramValue === '') {
                throw new CommandException("The value of $paramName is invalid");
            }
        }
    }

    private function validateIfParamsWereInformed(array $params, array $expectedParams): void 
    {    
        foreach ($expectedParams as $expectedParam) {
            if (!Helper::containsParam($params, $expectedParam)) {
                throw new CommandException("Not all parameters informed for the --create $this->entity command");
            }
        }
    }
}
