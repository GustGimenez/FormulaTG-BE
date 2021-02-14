<?php

namespace FormulaTG\Validators\Logic;

use FormulaTG\Models\RaceStatus;
use LogicException;

class ValidateOverviewLogic extends BaseValidateLogic implements ValidateLogic
{
    public function validate(array $params): void
    {
        $where = ['status_id' => ['=', RaceStatus::STARTED]];
        $raceStarted = $this->repository->get('race', ['*'], $where);

        if (empty($raceStarted)) {
            throw new LogicException('There is not started race!');
        }
    }
}
