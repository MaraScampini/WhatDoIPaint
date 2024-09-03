<?php

namespace App\Service\Brand;

use App\Entity\Brand;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class BrandService implements BrandServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    public function createBrand(array $brandData, User $user): void
    {
        $brand = new Brand();

        $brand->setUser($user)
            ->setName($brandData['name'])
            ->setGeneric(false);

        $this->em->persist($brand);
    }
}