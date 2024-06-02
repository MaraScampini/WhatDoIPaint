<?php

namespace App\Service\Brand;

use App\Entity\User;

interface BrandServiceInterface
{
    public function createBrand(array $brandData, User $user): void;
}