<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Models\Competitor;
use FormulaTG\Validators\Command\PositionCommandValidation;
use FormulaTG\Validators\Logic\ValidatePositionLogic;

class PositionCommand extends Command
{
    protected function validate(): void
    {
        $commandValidator = new PositionCommandValidation();
        $this->params = $commandValidator->validate($this->params);

        $logicValidator = new ValidatePositionLogic();
        $logicValidator->validate($this->params);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $competitors = $this->createCompetitors();
        $competitorsString = '';
        foreach ($competitors as $competitor) {
            $competitorsString .= $competitor->stringfy() . PHP_EOL;
        }

        return 'The competitors are in position:' . PHP_EOL . $competitorsString;
    }

    private function createCompetitors(): array
    {
        $carsIds = array_unique(explode(',', $this->params['cars']));
        $carsQuantity = count($carsIds);
        $competitors = [];

        for ($position = 0; $position < $carsQuantity; $position++) { 
            $newCompetitor = new Competitor($position + 1, $this->params['race'], $carsIds[$position]);
            if (!$this->repository->insert($newCompetitor, 'competitor', Competitor::getTableColumns())) {
                throw new Exception('Error on inserting competitor');
            }

            $competitors[] = $newCompetitor;
        }

        return $competitors;
    }
}