<?php 

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\RaceRepository;
use FormulaTG\Validators\Command\CountParams;
use FormulaTG\Validators\Logic\ValidateStartLogic;

class StartCommand extends Command
{
    protected function validate(): void
    {
        $expectedParams = ['race'];
        $validateParamsQuantity = new CountParams('start', $expectedParams);
        $validateParamsQuantity->validate($this->params);

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