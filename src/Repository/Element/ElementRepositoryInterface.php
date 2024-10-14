<?php

namespace App\Repository\Element;


interface ElementRepositoryInterface
{
    public function find(mixed $id, \Doctrine\DBAL\LockMode|int|null $lockMode = null, int|null $lockVersion = null): ?object;
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object;
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): ?array;
    public function getElementsByProjectId(int $projectId): ?array;
    public function getElementsBySquad(int $squadId): ?array;

    public function elementsByProjectIdSelector(int $projectId): ?array;

}