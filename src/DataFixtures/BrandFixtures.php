<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $brands = ['Games Workshop', 'Scale75', 'Mindwork Studio', 'In Motion Creation', 'Spiramirabilis', 'Other', '3D Printed'];

        foreach($brands as $brand) {
            $newBrand = new Brand();
            $newBrand->setName($brand);
            $newBrand->setGeneric(true);
            $manager->persist($newBrand);
        }
        $manager->flush();
    }
}