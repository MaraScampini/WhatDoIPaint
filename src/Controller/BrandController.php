<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\Brand\BrandRepositoryInterface;
use App\Service\Brand\BrandServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class BrandController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}
    #[Route('/brand/selector')]
    public function getBrandsSelector(BrandRepositoryInterface $brandRE): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $brands = $brandRE->getBrandsSelector($user);

        return new JsonResponse($brands);
    }

    #[Route('/brand', methods: ['POST'])]
    public function createBrand(Request $request, BrandServiceInterface $brandSE): Response
    {
        $brandData = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();
        
        $brandSE->createBrand($brandData, $user);

        try {
            $this->em->flush();
        } catch (\Exception $exception) {
            return new Response('Brand could not be saved');
        }
        return new Response('Brand created successfully');
    }
}
