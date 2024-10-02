<?php

namespace App\Service\Update;

use App\Repository\ElementUpdate\ElementUpdateRepositoryInterface;
use App\Repository\Image\ImageRepositoryInterface;
use App\Repository\Update\UpdateRepositoryInterface;

class UpdateService implements UpdateServiceInterface
{
    public function __construct(
        private readonly UpdateRepositoryInterface $updateRepository,
        private readonly ImageRepositoryInterface $imageRepository,
        private readonly ElementUpdateRepositoryInterface $elementUpdateRepository
    )
    {}
    public function getUpdatesByProjectId(int $projectId, int $page = 1): array
    {
        $updates = $this->updateRepository->getUpdatesByProjectId($projectId, $page);
        return $this->addImagesAndElementsToUpdates($updates);
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
}