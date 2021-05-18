<?php

namespace FormulaTG\Repositories;

use Exception;
use FormulaTG\Models\Competitor;

class CompetitorRepository extends GenericRepository
{
    public function getCompetitorsInRace(int $raceId, array $carsIds): array
    {
        $where = ['race_id' => ['=', $raceId]];
        $competitors = $this->get('competitor', ['*'], $where);

        $competitorsInRace = [];
        foreach ($carsIds as $carId) {
            $competitorInRaceAux = array_filter($competitors, function($competitor) use ($carId) {
                return intval($competitor['car_id']) === intval($carId);
            });
            $competitorsInRace = array_merge($competitorsInRace, $competitorInRaceAux);
        }

        return array_map(function($competitor) {
            return Competitor::populate($competitor);
        },
        $competitorsInRace);
    }

    public function updatePosition(int $id, int $position): bool
    {
        $stmt = $this->conn->prepare("UPDATE competitor SET position = " . $position . " WHERE id = {$id}");

        try {
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
