<?php

namespace App\Service\Reference;

use App\Entity\Project;
use App\Entity\Reference;
use App\Exception\CustomMessageException;
use App\Exception\EntityNotFoundException;
use App\Repository\Project\ProjectRepositoryInterface;
use App\Service\Imgur\ImgurService;
use App\Service\Reference\ReferenceServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ReferenceService implements ReferenceServiceInterface
{

    public function __construct(
        private readonly ImgurService               $imgurSE,
        private readonly ProjectRepositoryInterface $projectRE,
        private readonly EntityManagerInterface     $em
    )
    {
    }

    public function createReferences(array $referenceData): void
    {
        $projectId = $referenceData['projectId'];
        if (!$projectId) throw new EntityNotFoundException('Project');

        $project = $this->projectRE->find($projectId);

        if (isset($referenceData['images'])) {
            foreach ($referenceData['images'] as $image) {
                $imageURL = $this->imgurSE->uploadImage($image);
                $reference = new Reference();
                $reference->setProject($project);
                $reference->setImage($imageURL);
                $this->em->persist($reference);
            }
        }

        if (isset($referenceData['urls'])) {
            foreach ($referenceData['urls'] as $url) {
                $reference = new Reference();
                $reference->setProject($project);
                $reference->setUrl($url);
                $this->em->persist($reference);
            }
        }
    }
}