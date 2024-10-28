<?php

namespace App\Service\Imgur;

use App\Exception\CustomMessageException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImgurService
{
    private $client;
    private $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
        $this->client = HttpClient::create();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws CustomMessageException
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function uploadImage(string $image)
    {
        $base64Data = explode(',', $image)[1];
        $binaryData = base64_decode($base64Data);

        $response = $this->client->request('POST', 'https://api.imgur.com/3/image', [
            'headers' => [
                'Authorization' => 'Client-ID ' . $this->clientId,
                'Content-Type' => 'application/octet-stream'
            ],
            'body' => $binaryData
        ]);

        $data = $response->toArray();

        if (isset($data['success']) && $data['success']) {
            return $data['data']['link'];
        }

        throw new CustomMessageException('Image upload failed');
    }
}