<?php

namespace Pandisoft\TawssilApiClient;

use GuzzleHttp\Client;

class TawssilApiClient
{
    protected $client;

    const ENDPOINTS = [
        'create_package' => '/api/create_colis/',
        'update_package' => '/api/update_colis/',
        'tracking_package' => '/api/tracking_colis/',
        'create_address' => '/api/create_address/',
        'get_package_label' => '/api/get_colis_label/',
        'get_rates' => '/api/get_rates/'
    ];

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

    public function createPackage(array $data)
    {
        return $this->post(self::ENDPOINTS['create_package'], $data);
    }

    public function updatePackage($packageId, array $data)
    {
        return $this->post(self::ENDPOINTS['update_package'], $data);
    }

    public function trackingPackage($data)
    {
        return $this->post(self::ENDPOINTS['tracking_package'], $data);
    }

    public function createAddress(array $data)
    {
        return $this->post(self::ENDPOINTS['create_address'], $data);
    }

    public function getPackageLabel($data)
    {
        return $this->post(self::ENDPOINTS['get_package_label'], $data);
    }

    public function getRates($data)
    {
        return $this->post(self::ENDPOINTS['get_rates'], $data);
    }

    private function post($url, array $data)
    {
        $response = $this->client->post($url, ['json' => $data]);

        return json_decode($response->getBody(), true);
    }

    private function get($url)
    {
        $response = $this->client->get($url);

        return json_decode($response->getBody(), true);
    }
}
