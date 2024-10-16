<?php

namespace App\Service\Update;

use App\Entity\Element;
use App\Entity\ElementUpdate;
use App\Entity\Image;
use App\Entity\Project;
use App\Entity\Squad;
use App\Entity\Update;
use App\Exception\EntityNotFoundException;
use App\Repository\Element\ElementRepositoryInterface;
use App\Repository\ElementUpdate\ElementUpdateRepositoryInterface;
use App\Repository\Image\ImageRepositoryInterface;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\Squad\SquadRepositoryInterface;
use App\Repository\Update\UpdateRepositoryInterface;
use App\Service\Imgur\ImgurService;
use Doctrine\ORM\EntityManagerInterface;

class UpdateService implements UpdateServiceInterface
{
    public function __construct(
        private readonly UpdateRepositoryInterface $updateRepository,
        private readonly ImageRepositoryInterface $imageRepository,
        private readonly ElementUpdateRepositoryInterface $elementUpdateRepository,
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly ElementRepositoryInterface $elementRepository,
        private readonly SquadRepositoryInterface $squadRepository,
        private readonly ImgurService $imgurService,
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

    public function createShortUpdate(int $projectId): Update
    {
        $project = $this->projectRepository->find($projectId);
        if(!$project instanceof Project) throw new EntityNotFoundException('Project');

        $update = new Update();
        $update->setProject($project);
        $update->setDate(new \DateTime());
        $update->setLastUpdate(new \DateTime());
        $project->setLastUpdate(new \DateTime());
        $this->em->persist($update);
        return $update;
    }

    public function createUpdate(array $request): void
    {
        $projectId = $request['projectId'];
        $newUpdate = $this->createShortUpdate($projectId);

        if(isset($request['images'])) {
            foreach($request['images'] as $image) {
                $imageURL = $this->imgurService->uploadImage($image);
                $newImage = new Image();
                $newImage->setUrl($imageURL);
                $newImage->setNewUpdate($newUpdate);
                $this->em->persist($newImage);
            }
        }

        isset($request['title']) ? $newUpdate->setTitle($request['title']) : $newUpdate->setTitle('Painted today');
        isset($request['description']) ?? $newUpdate->setDescription($request['description']);

        if(isset($request['elements'])) {
            foreach($request['elements'] as $elementId) {
                $element = $this->elementRepository->find($elementId);
                if(!$element instanceof Element) throw new EntityNotFoundException('Element');

                $elementUpdate = new ElementUpdate();
                $elementUpdate->setNewUpdate($newUpdate);
                $elementUpdate->setElement($element);
                $this->em->persist($elementUpdate);
            }
        }

        if(isset($request['squads'])) {
            foreach($request['squads'] as $squadId) {
                $squad = $this->squadRepository->find($squadId);
                if(!$squad instanceof Squad) throw new EntityNotFoundException('Squad');

                $elementUpdate = new ElementUpdate();
                $elementUpdate->setNewUpdate($newUpdate);
                $elementUpdate->setSquad($squad);
                $this->em->persist($elementUpdate);
            }
        }

    }
}