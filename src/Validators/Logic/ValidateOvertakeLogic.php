<?php

namespace FormulaTG\Validators\Logic;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\Race;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\CompetitorRepository;
use FormulaTG\Repositories\GenericRepository;
use LogicException;

class ValidateOvertakeLogic implements ValidateLogic
{
    private GenericRepository $repository;
    private CompetitorRepository $competitorRepository;

    public function __construct()
    {
        $conn = Connection::createConnection();
        $this->repository = new GenericRepository($conn);
        $this->competitorRepository = new CompetitorRepository($conn);
    }

    public function validate(array $params): void
    {
        $race = $this->checkForStartedRace();
        $competitors = $this->competitorRepository->getCompetitorsInRace(
            $race->getId(),
            [
                $params['overtaking'],
                $params['overtaken'],
            ]
        );

        $this->checkIfCarsExist([
            $params['overtaking'],
            $params['overtaken'],
        ]);
        $this->checkIfCompetitorsAreInTheRace(
            $competitors,
            [
                $params['overtaking'],
                $params['overtaken'],
            ]
        );
        $this->checkForAdjacency($competitors, $params['overtaking'], $params['overtaken']);
    }

    private function checkForStartedRace(): Race
    {
        $where = ['status_id' => ['=', RaceStatus::STARTED]];
        $raceStarted = $this->repository->get('race', ['*'], $where);

        if (empty($raceStarted)) {
            throw new LogicException('There is no race started');
        }

        return Race::populate($raceStarted[0]);
    }

    private function checkIfCarsExist(array $carsIds): void
    {
        $cars = $this->repository->listAll('car');
        if (empty($cars)) {
            throw new LogicException('There are no cars registered');
        }

        $registeredIds = [];
        foreach ($cars as $car) {
            $registeredIds[] = $car['id'];
        }

        foreach ($carsIds as $carId) {
            if (!in_array($carId, $registeredIds)) {
                throw new LogicException("Car n° $carId is not registered");
            }
        }
    }

    private function checkIfCompetitorsAreInTheRace(array $competitors, array $carsIds): void
    {
        $competitorsCars = [];
        foreach ($competitors as $competitor) {
            $competitorsCars[] = $competitor->getCarId();
        }

        foreach ($carsIds as $carId) {
            if (!in_array($carId, $competitorsCars)) {
                throw new LogicException("Car nº $carId is not competing in this race");
            }
        }
    }

    private function checkForAdjacency(
        array $competitors,
        int $overtakingId, 
        int $overtakenId
    ): void {
        $overtaking = array_filter($competitors, function($competitor) use ($overtakingId) {
            return $competitor->getCarId() === $overtakingId;
        });
        $overtaking = array_values($overtaking)[0];

        $overtaken = array_filter($competitors, function($competitor) use ($overtakenId) {
            return $competitor->getCarId() === $overtakenId;
        });
        $overtaken = array_values($overtaken)[0];

        if ($overtaken->getPosition() > $overtaking->getPosition()) {
            throw new LogicException("Car nº {$overtaken->getCarId()} is behind car nº {$overtaking->getCarId()}");
        }

        if (abs($overtaking->getPosition() - $overtaken->getPosition()) !== 1) {
            throw new LogicException('The competitors are not adjacent');
        }
    }
}