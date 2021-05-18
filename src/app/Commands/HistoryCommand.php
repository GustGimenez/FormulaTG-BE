<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Models\Overtake;
use FormulaTG\Utils\Helper;
use FormulaTG\Validators\Command\CountParams;
use FormulaTG\Validators\Logic\ValidateHistoryLogic;

class HistoryCommand extends Command
{
    protected function validate(): void
    {
        $expectedParams = ['race'];
        
        $validateParamsQuantity = new CountParams('start', $expectedParams);
        $validateParamsQuantity->validate($this->params);

        $logicValidator = new ValidateHistoryLogic();
        $logicValidator->validate($this->params);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $overtakes = $this->getRaceOvertakes();
        $result = '';

        foreach ($overtakes as $overtake) {
            $result .= $overtake->stringfy() . PHP_EOL;
        }

        return "Race {$this->params[0]} history: " . PHP_EOL . $result;
    }

    /**
     * @return Overtake[]
     */
    private function getRaceOvertakes(): array
    {
        $where = ['race_id' => ['=', $this->params[0]]];
        $overtakesData = $this->repository->get('overtake', ['*'], $where);
        $overtakes = [];

        foreach ($overtakesData as $overtakeData) {
            $overtakes[] = Overtake::populate($overtakeData);
        }

        return $overtakes;
    }
}
