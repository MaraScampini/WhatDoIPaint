<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $statuses = ['Box', 'Sprue', 'Printed', 'Assembled', 'Primed', 'Half-painted', 'Painted', 'Finished'];

        foreach ($statuses as $status) {
            $newStatus = new Status();
            $newStatus->setName($status);
            $manager->persist($newStatus);
        }

        $manager->flush();
    }
}