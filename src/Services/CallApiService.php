<?php
namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

Class CallApiService 
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client= $client;
    }

    public function GetFranceData(): array 
    {
        $response = $this->client->request(
            'GET',
            'https://geo.api.gouv.fr/communes'
        );
        return $response->toArray();
    }
}