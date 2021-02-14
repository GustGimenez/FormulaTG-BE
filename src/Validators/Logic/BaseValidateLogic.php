<?php

namespace FormulaTG\Validators\Logic;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Repositories\GenericRepository;

abstract class BaseValidateLogic
{
    protected GenericRepository $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(Connection::createConnection());
    }
}
