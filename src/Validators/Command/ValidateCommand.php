<?php

namespace FormulaTG\Validators\Command;

interface ValidateCommand
{
    public function validate(array $params): array;
}
