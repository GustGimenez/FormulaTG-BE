<?php

namespace FormulaTG\Validators\Command;

interface CommandValidator
{
    public function setNext(CommandValidator $validator): void;

    public function validate(array $params): void;
} 
