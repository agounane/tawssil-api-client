<?php

namespace Pandisoft\TawssilApiClient;

use GuzzleHttp\Client;

class TawssilApiClient
{
    protected $client;

    public function __construct($baseUrl, $jwtToken)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $jwtToken,
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function createColis(array $data)
    {
        $response = $this->client->post('/api/create_colis/', ['json' => $data]);

        return json_decode($response->getBody(), true);
    }

    public function updateColis($colisId, array $data)
    {
        $response = $this->client->put("/api/update_colis/{$colisId}", ['json' => $data]);

        return json_decode($response->getBody(), true);
    }

    public function trackingColis($colisId)
    {
        $response = $this->client->get("/api/tracking_colis/{$colisId}");

        return json_decode($response->getBody(), true);
    }

    public function createAddress(array $data)
    {
        $response = $this->client->post('/api/create_address/', ['json' => $data]);

        return json_decode($response->getBody(), true);
    }

    public function getColisLabel($colisId)
    {
        $response = $this->client->get("/api/get_colis_label/{$colisId}");

        return json_decode($response->getBody(), true);
    }
}
