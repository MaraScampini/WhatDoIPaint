<?php

namespace App\Service\Squad;

use App\Entity\Squad;
use App\Entity\SquadStatus;
use App\Exception\CustomMessageException;
use App\Repository\Squad\SquadStatusRepositoryInterface;
use App\Repository\Status\StatusRepositoryInterface;
use App\Service\Squad\SquadStatusServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class SquadStatusService implements SquadStatusServiceInterface
{
    public function __construct(
        private readonly SquadStatusRepositoryInterface $squadStatusRepository,
        private readonly StatusRepositoryInterface $statusRepository,
        private readonly EntityManagerInterface $em
    )
    {}

    public function updateSquadStatuses(Squad $squad, array $elements): void
    {
        foreach($elements as $element) {
            // GET STATUS ENTITY
            $statusEntity = $this->statusRepository->find($element['status']);

            // FIND CURRENT SQUAD STATUS REGISTER AND SET AMOUNT
            $existingStatus = $this->squadStatusRepository->findOneBy(['squad' => $squad, 'status' => $statusEntity]);
            $existingStatus && $existingStatus->setAmount($element['amount']);

            // IF IT DOES NOT EXIST AND THE AMOUNT TO SET IS MORE THAN ZERO, CREATE A NEW REGISTER
            if(!$existingStatus instanceof SquadStatus && $element['amount'] !== 0) {
                $newStatus = new SquadStatus();
                $newStatus->setSquad($squad);
                $newStatus->setStatus($statusEntity);
                $newStatus->setAmount($element['amount']);
                $this->em->persist($newStatus);
            }
        }
    }
}