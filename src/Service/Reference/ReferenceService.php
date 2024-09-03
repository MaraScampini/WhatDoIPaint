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
        private readonly ImgurService $imgurSE,
        private readonly ProjectRepositoryInterface $projectRE,
        private readonly EntityManagerInterface $em
    ) {}


    public function createReference(array $referenceData): void
    {
        $reference = new Reference();

        $projectId = $referenceData['projectId'];
        if(!$projectId) throw new EntityNotFoundException('Project');

        $project = $this->projectRE->find($projectId);
        $reference->setProject($project);

        if(isset($referenceData['image'])) {
            $imageURL = $this->imgurSE->uploadImage($referenceData['image']);
            $reference->setImage($imageURL);
        }

        if(isset($referenceData['url'])) {
            $reference->setUrl($referenceData['url']);
        }

        $this->em->persist($reference);
    }
}