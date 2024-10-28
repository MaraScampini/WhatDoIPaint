<?php

namespace App\Service\Squad;

use App\Entity\Squad;
use App\Exception\EntityNotFoundException;
use App\Repository\Squad\SquadRepositoryInterface;
use App\Service\Squad\SquadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

class SquadService implements SquadServiceInterface
{
    public function __construct(
        private readonly SquadRepositoryInterface $squadRepository,
        private readonly EntityManagerInterface $em
    ){}

    public function removeSquad(int $squadId): void
    {
        $squad = $this->squadRepository->find($squadId);
        if(!$squad instanceof Squad) throw new EntityNotFoundException('Squad');

        $this->em->remove($squad);
    }
}