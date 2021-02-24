<?php

namespace FormulaTG\Validators\Command;

abstract class BaseCommandValidator implements CommandValidator
{
    protected string $commandName;
    protected array $expectedParams;
    protected ?CommandValidator $next;

    public function __construct(
        string $commandName,
        array $expectedParams = []
    ) {
        $this->next = null;
        $this->commandName = $commandName;
        $this->expectedParams = $expectedParams;
    }

    public function setNext(CommandValidator $validator): void
    {
        $this->next = $validator;
    }

    public function validate(array $params): void
    {
        if ($this->next !== null) {
            $this->next->validate($params);
        }
    }
}
