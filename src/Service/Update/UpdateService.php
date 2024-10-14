<?php

namespace App\Service\Update;

use App\Entity\Project;
use App\Entity\Update;
use App\Exception\EntityNotFoundException;
use App\Repository\ElementUpdate\ElementUpdateRepositoryInterface;
use App\Repository\Image\ImageRepositoryInterface;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\Update\UpdateRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UpdateService implements UpdateServiceInterface
{
    public function __construct(
        private readonly UpdateRepositoryInterface $updateRepository,
        private readonly ImageRepositoryInterface $imageRepository,
        private readonly ElementUpdateRepositoryInterface $elementUpdateRepository,
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly EntityManagerInterface $em
    )
    {}
    public function getUpdatesByProjectId(int $projectId, int $page = 1): array
    {
        $updates = $this->updateRepository->getUpdatesByProjectId($projectId, $page);
        return $this->addImagesAndElementsToUpdates($updates);
    }

    public function getUpdateInformation(int $updateId): array
    {
        $update = $this->updateRepository->getUpdateInformation($updateId);
        $update['images'] = $this->imageRepository->getImagesByUpdateId($updateId);
        $update['elements'] = $this->elementUpdateRepository->getElementsAndSquadsByUpdateId($updateId);

        return $update;

    }

    private function addImagesAndElementsToUpdates(array $updates): array
    {
        foreach($updates as &$update) {
            $images = $this->imageRepository->getImagesByUpdateId($update['id']);
            $elements = $this->elementUpdateRepository->getElementsAndSquadsByUpdateId($update['id']);
            $update['images'] = $images;
            $update['elements'] = $elements;
        }

        return $updates;
    }

    public function createShortUpdate(int $projectId): void
    {
        $project = $this->projectRepository->find($projectId);
        if(!$project instanceof Project) throw new EntityNotFoundException('Project');

        $update = new Update();
        $update->setProject($project);
        $update->setDate(new \DateTime());
        $update->setLastUpdate(new \DateTime());
        $this->em->persist($update);
    }
}