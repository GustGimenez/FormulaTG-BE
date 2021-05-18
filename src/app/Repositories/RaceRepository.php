<?php

namespace FormulaTG\Repositories;

use Exception;

class RaceRepository extends GenericRepository
{
    public function changeStatus(int $raceId, int $status): bool
    {
        $stmt = $this->conn->prepare("UPDATE race SET status_id = " . $status . " WHERE id = {$raceId}");

        try {
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}