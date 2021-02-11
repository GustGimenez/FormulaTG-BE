<?php

namespace FormulaTG\Commands;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Repositories\GenericRepository;

abstract class Command
{
    protected array $params;
    protected GenericRepository $repository;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->repository = new GenericRepository(Connection::createConnection());
    }

    protected abstract function validate(): void;

    public abstract function execute(): string;
}
