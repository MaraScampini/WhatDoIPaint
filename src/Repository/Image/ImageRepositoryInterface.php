<?php

namespace App\Repository\Image;
interface ImageRepositoryInterface
{
    public function find(mixed $id, \Doctrine\DBAL\LockMode|int|null $lockMode = null, int|null $lockVersion = null): ?object;
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object;
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): ?array;
    public function getImagesByUpdateId(int $updateId): ?array;
    public function getImagesByProjectId(int $projectId, int $page = 1, int $limit = 5): ?array;
    public function getProjectCoverImage(int $projectId): array;

}