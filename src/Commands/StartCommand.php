<?php 

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\RaceRepository;
use FormulaTG\Validators\Command\StartCommandValidation;
use FormulaTG\Validators\Logic\ValidateStartLogic;

class StartCommand extends Command
{
    protected function validate(): void
    {
        $commandValidator = new StartCommandValidation();
        $commandValidator->validate($this->params);

        $logicValidator = new ValidateStartLogic();
        $logicValidator->validate($this->params);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $ce) {
            return $ce->getMessage();
        }

        $this->startRace();

        return 'Race started!';
    }

    private function startRace(): void
    {
        $raceRepository = new RaceRepository(Connection::createConnection());
        $raceRepository->changeStatus($this->params[0], RaceStatus::STARTED);
    }
}