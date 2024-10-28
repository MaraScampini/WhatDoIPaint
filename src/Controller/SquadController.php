<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Squad\SquadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class SquadController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {}
    #[Route('/squad/{squadId}', methods: ['DELETE'])]
    public function removeSquad(SquadServiceInterface $squadService, int $squadId): Response
    {
        $squadService->removeSquad($squadId);

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return new Response('Squad could not be deleted', 500);
        }

        return new Response('Squad deleted successfully', 200);
    }
}
