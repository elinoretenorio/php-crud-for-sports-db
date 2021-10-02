<?php

declare(strict_types=1);

namespace Sports\BasketballEventStates;

use Sports\Database\IDatabase;
use Sports\Database\DatabaseException;

class BasketballEventStatesRepository implements IBasketballEventStatesRepository
{
    private IDatabase $db;

    public function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function insert(BasketballEventStatesDto $dto): int
    {
        $sql = "INSERT INTO `basketball_event_states` (`event_id`, `current_state`, `sequence_number`, `period_value`, `period_time_elapsed`, `period_time_remaining`, `context`)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $result = $this->db->prepare($sql);
            $result->execute([
                $dto->eventId,
                $dto->currentState,
                $dto->sequenceNumber,
                $dto->periodValue,
                $dto->periodTimeElapsed,
                $dto->periodTimeRemaining,
                $dto->context
            ]);

            return $this->db->lastInsertId();
        } catch (DatabaseException $exception) {
            return -1;
        }
    }

    public function update(BasketballEventStatesDto $dto): int
    {
        $sql = "UPDATE `basketball_event_states` SET `event_id` = ?, `current_state` = ?, `sequence_number` = ?, `period_value` = ?, `period_time_elapsed` = ?, `period_time_remaining` = ?, `context` = ?
                WHERE `id` = ?";

        try {
            $result = $this->db->prepare($sql);
            $result->execute([
                $dto->eventId,
                $dto->currentState,
                $dto->sequenceNumber,
                $dto->periodValue,
                $dto->periodTimeElapsed,
                $dto->periodTimeRemaining,
                $dto->context,
                $dto->id
            ]);

            return $result->rowCount();
        } catch (DatabaseException $exception) {
            return -1;
        }
    }

    public function get(int $id): ?BasketballEventStatesDto
    {
        $sql = "SELECT `id`, `event_id`, `current_state`, `sequence_number`, `period_value`, `period_time_elapsed`, `period_time_remaining`, `context`
                FROM `basketball_event_states` WHERE `id` = ?";

        try {
            $result = $this->db->prepare($sql);
            $result->execute([$id]);
            $row = $result->fetchAll();

            return (!empty($row)) ? new BasketballEventStatesDto($row[0]) : null;
        } catch (DatabaseException $exception) {
            return null;
        }
    }

    public function getAll(): array
    {
        $sql = "SELECT `id`, `event_id`, `current_state`, `sequence_number`, `period_value`, `period_time_elapsed`, `period_time_remaining`, `context`
                FROM `basketball_event_states`";

        try {
            $result = $this->db->prepare($sql);
            $result->execute();
            $rows = $result->fetchAll();

            $result = [];
            foreach ($rows as $row) {
                $result[] = new BasketballEventStatesDto($row);
            }

            return $result;
        } catch (DatabaseException $exception) {
            return [];
        }
    }

    public function delete(int $id): int
    {
        $sql = "DELETE FROM `basketball_event_states` WHERE `id` = ?";

        try {
            $result = $this->db->prepare($sql);
            $result->execute([$id]);

            return $result->rowCount();
        } catch (DatabaseException $exception) {
            return -1;
        }
    }
}