<?php

namespace App\Service\Element;

use App\Entity\Element;
use App\Exception\EntityNotFoundException;
use App\Repository\Element\ElementRepositoryInterface;
use App\Service\Element\ElementServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class ElementService implements ElementServiceInterface
{
    public function __construct(
        private readonly ElementRepositoryInterface $elementRepository,
        private readonly EntityManagerInterface $em
    ){}
    public function removeElement(int $elementId): void
    {
        $elementEntity = $this->elementRepository->find($elementId);
        if(!$elementEntity instanceof Element) throw new EntityNotFoundException('Element');

        $this->em->remove($elementEntity);
    }

}