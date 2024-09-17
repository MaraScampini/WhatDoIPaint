<?php

namespace App\DataFixtures;

use App\Entity\Technique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechniqueFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $techniques = ['Layering', 'NMM', 'OSL', 'Skin', 'Fabric', 'Leather', 'Gems', 'Weathering', 'Wet Blending',
            'Dry Brush', 'Basing', 'Stippled Blending', 'TMM', 'Hair / Fur'];
        foreach ($techniques as $technique) {
            $newTechnique = new Technique();
            $newTechnique->setName($technique);
            $manager->persist($newTechnique);
        }

        $manager->flush();
    }
}