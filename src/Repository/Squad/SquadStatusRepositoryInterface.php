<?php

namespace App\Repository\Squad;

use App\Entity\Squad;

interface SquadStatusRepositoryInterface
{
    public function find(mixed $id, \Doctrine\DBAL\LockMode|int|null $lockMode = null, int|null $lockVersion = null): ?object;

    public function findOneBy(array $criteria, ?array $orderBy = null): ?object;

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): ?array;

    public function getElementsBySquad(int $squadId): ?array;

    public function getFinishedElementsBySquad(Squad $squad): ?int;

}