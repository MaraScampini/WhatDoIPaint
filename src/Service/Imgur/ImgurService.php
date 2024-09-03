<?php

namespace App\Service;

use App\Exception\CustomMessageException;
use Symfony\Component\HttpClient\HttpClient;

class ImgurService
{
    private $client;
    private $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
        $this->client = HttpClient::create();
    }

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