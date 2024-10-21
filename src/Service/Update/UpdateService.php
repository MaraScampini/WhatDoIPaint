<?php

namespace App\Service\Update;

use App\Entity\Element;
use App\Entity\ElementUpdate;
use App\Entity\Image;
use App\Entity\Project;
use App\Entity\Squad;
use App\Entity\Status;
use App\Entity\Update;
use App\Entity\User;
use App\Exception\CustomMessageException;
use App\Exception\EntityNotFoundException;
use App\Repository\Element\ElementRepositoryInterface;
use App\Repository\ElementUpdate\ElementUpdateRepositoryInterface;
use App\Repository\Image\ImageRepositoryInterface;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Repository\Squad\SquadRepositoryInterface;
use App\Repository\Squad\SquadStatusRepositoryInterface;
use App\Repository\Status\StatusRepositoryInterface;
use App\Repository\Update\UpdateRepositoryInterface;
use App\Service\Imgur\ImgurService;
use App\Service\Squad\SquadStatusServiceInterface;
use App\Service\Streak\StreakServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class UpdateService implements UpdateServiceInterface
{
    public function __construct(
        private readonly UpdateRepositoryInterface        $updateRepository,
        private readonly ImageRepositoryInterface         $imageRepository,
        private readonly ElementUpdateRepositoryInterface $elementUpdateRepository,
        private readonly ProjectRepositoryInterface       $projectRepository,
        private readonly ElementRepositoryInterface       $elementRepository,
        private readonly SquadRepositoryInterface         $squadRepository,
        private readonly SquadStatusServiceInterface $squadStatusService,
        private readonly ImgurService                     $imgurService,
        private readonly StreakServiceInterface           $streakService,
        private readonly EntityManagerInterface           $em, private readonly StatusRepositoryInterface $statusRepository
    )
    {
    }

    public function getUpdatesForGeneralProjectByProjectId(int $projectId): array
    {
        $updates = $this->updateRepository->getUpdatesForGeneralProjectEndpoint($projectId);
        return $this->addImagesAndElementsToUpdates($updates);
    }

    public function getPaginatedUpdatesByProjectId(int $projectId, int $page, int $limit): ?array
    {
        $updates = $this->updateRepository->getUpdatesByProjectId($projectId, $page, $limit);
        $updates['data'] = $this->addImagesAndElementsToUpdates($updates, true);

        $updates['data'] = array_map(function ($update) {
            if ($update['date'] instanceof \DateTime) {
                $update['date'] = $update['date']->format('d/m/Y');
            } else {
                $update['date'] = (new \DateTime($update['date']))->format('d/m/Y');
            }
            return $update;
        }, $updates['data']);

        return $updates;
    }

    public function getUpdateInformation(int $updateId): array
    {
        $update = $this->updateRepository->getUpdateInformation($updateId);
        $update['images'] = $this->imageRepository->getImagesByUpdateId($updateId);
        $update['elements'] = $this->elementUpdateRepository->getElementsAndSquadsByUpdateId($updateId);

        return $update;

    }

    private function addImagesAndElementsToUpdates(array $updates, bool $isPaginated = false): array
    {
        if ($isPaginated) {
            $updateData = $updates['data'];
        } else {
            $updateData = $updates;
        }

        foreach ($updateData as &$update) {
            $images = $this->imageRepository->getImagesByUpdateId($update['id']);
            $elements = $this->elementUpdateRepository->getElementsAndSquadsByUpdateId($update['id']);
            $update['images'] = $images;
            $update['elements'] = $elements;
        }

        return $updateData;
    }

    public function createShortUpdate(int $projectId, User $user): Update
    {
        $project = $this->projectRepository->find($projectId);
        if (!$project instanceof Project) throw new EntityNotFoundException('Project');

        $update = new Update();
        $update->setProject($project);
        $update->setDate(new \DateTime());
        $update->setLastUpdate(new \DateTime());
        $project->setLastUpdate(new \DateTime());
        $this->em->persist($update);

        $this->streakService->createOrUpdateStreak($user);

        return $update;
    }

    public function createUpdate(array $request, User $user): void
    {
        $projectId = $request['projectId'];
        $newUpdate = $this->createShortUpdate($projectId, $user);

        if (isset($request['images'])) {
            foreach ($request['images'] as $image) {
                $imageURL = $this->imgurService->uploadImage($image);
                $newImage = new Image();
                $newImage->setUrl($imageURL);
                $newImage->setNewUpdate($newUpdate);
                $this->em->persist($newImage);
            }
        }

        isset($request['title']) ? $newUpdate->setTitle($request['title']) : $newUpdate->setTitle('Painted today');
            isset($request['description']) ?? $newUpdate->setDescription($request['description']);

        if (isset($request['elements'])) {
            foreach ($request['elements'] as $elementToUpdate) {
                $elementId = $elementToUpdate['id'];
                $element = $this->elementRepository->find($elementId);
                if (!$element instanceof Element) throw new EntityNotFoundException('Element');

                $elementProject = $element->getProject();
                if($elementProject->getId() !== $projectId) throw new CustomMessageException('That element does not belong to that project');

                $elementUpdate = new ElementUpdate();
                $elementUpdate->setNewUpdate($newUpdate);
                $elementUpdate->setElement($element);
                $this->em->persist($elementUpdate);

                $newStatusId = $elementToUpdate['status'];
                $newStatus = $this->statusRepository->find($newStatusId);
                if(!$newStatus instanceof Status) throw new EntityNotFoundException('Status');
                $element->setStatus($newStatus);
            }
        }

        if (isset($request['squads'])) {
            foreach ($request['squads'] as $squad) {
                $squadId = $squad['id'];
                $squadEntity = $this->squadRepository->find($squadId);
                if (!$squadEntity instanceof Squad) throw new EntityNotFoundException('Squad');

                $this->squadStatusService->updateSquadStatuses($squadEntity, $squad['elements']);
            }
        }
    }
}