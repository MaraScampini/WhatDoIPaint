<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\Streak\StreakServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class StreakController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }
    #[Route('/streak', methods: ['POST'])]
    public function updateStreak(StreakServiceInterface $streakService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $streakCount = $streakService->createOrUpdateStreak($user);

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            return new Response('The streak could not be updated', 500);
        }

        return new Response((string)$streakCount);
    }
}
