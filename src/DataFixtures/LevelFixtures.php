<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $levels = ['Relaxed', 'Focused', 'All out', 'Competition', 'Army painting'];

        foreach($levels as $level) {
            $newLevel = new Level();
            $newLevel->setName($level);
            $manager->persist($newLevel);
        }
        $manager->flush();
    }
}