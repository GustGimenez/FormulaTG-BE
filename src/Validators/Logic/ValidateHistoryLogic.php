<?php

namespace FormulaTG\Validators\Logic;

use FormulaTG\Models\Race;
use FormulaTG\Models\RaceStatus;
use LogicException;

class ValidateHistoryLogic extends BaseValidateLogic implements ValidateLogic
{
    public function validate(array $params): void
    {
        $raceData = $this->repository->getById('race', ['*'], $params[0]);

        if (!$raceData || empty($raceData)) {
            throw new LogicException('The race informed does not exist');
        }

        /** @var Race $race */
        $race = Race::populate($raceData);

        if ($race->getStatus()->getId() !== RaceStatus::FINESHED) {
            throw new LogicException('The race is not finished');
        }
    }
}
