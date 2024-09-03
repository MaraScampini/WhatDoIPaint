<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Reference\ReferenceServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api')]
class ReferenceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}
    #[Route('/reference', methods: ['POST'])]
    public function createReference(Request $request, ReferenceServiceInterface $referenceSE): Response
    {
        $referenceData = json_decode($request->getContent(), true);

        $referenceSE->createReferences($referenceData);

        try {
            $this->em->flush();
        } catch (\Exception $exception) {
            return new Response('Reference could not be saved', 500);
        }
        return new Response('Reference created successfully');
    }
}
