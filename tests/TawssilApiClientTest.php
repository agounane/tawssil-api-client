<?php

namespace Tests;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Pandisoft\TawssilApiClient\TawssilApiClient;

class TawssilApiClientTest extends TestCase
{
    protected $apiurl;
    protected $client;
    protected $token;
    protected $partnerId;
    protected $apiClient;

    protected function setUp(): void
    {
        parent::setUp();

        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->token = $_ENV['TAWSSIL_API_TOKEN'];
        $this->apiurl = $_ENV['API_URL'];
        $this->partnerId = $_ENV['PARTNER_ID'];

        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'success',
                'data' => []
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $this->client = new Client([
            'handler' => $handlerStack,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->apiClient = new TawssilApiClient($this->apiurl, $this->token, $this->client);
    }

    public function testCreatePackage()
    {
        // Dummy data may not always work :)
        $data = [
            "partner_id" => (int)$this->partnerId,
            "data" => [
                [
                    "picking_address" => [
                        "address_reference" => "clt0020"
                    ],
                    "recipient_address" => [
                        "city" => 42,
                        "street" => "Rue  2",
                        "street2" => "Bv Testing address",
                        "name" => "UnitTest via API",
                        "phone" => "+212000000"
                    ],
                    "shipment_type" => "relay_point",
                    "cash_on_delivery" => 1000,
                    "parcel_reference" => "Cl" . microtime(),
                    "barcode" => microtime()
                ]
            ]
        ];

        $response = $this->apiClient->createPackage($data);
        $this->assertEquals('OK', $response['result']['result']);
    }

    public function testUpdatePackage()
    {
        // Dummy data may not always work :)
        $data = [
            "partner_id" => (int)$this->partnerId,
            "data" => [
                [
                    "picking_address" => [
                        "address_reference" => "clt0020"
                    ],
                    "recipient_address" => [
                        "city" => 42,
                        "street" => "Rue  2",
                        "street2" => "Bv Testing address",
                        "name" => "UnitTest via API",
                        "phone" => "+212000000"
                    ],
                    "shipment_type" => "relay_point",
                    "cash_on_delivery" => 1000,
                    "parcel_reference" => "Cl" . microtime(),
                    "barcode" => microtime()
                ]
            ]
        ];

        $response = $this->apiClient->createPackage($data);
        $this->assertEquals('OK', $response['result']['result']);

        $updateData = [
            "partner_id" => (int)$this->partnerId,
            "parcel_reference" => $response['result']['data'][0]['parcel_reference'],
            "data" => [
                "cash_on_delivery" => 1500
            ]
        ];

        $response = $this->apiClient->updatePackage($response['result']['data'][0]['id'], $updateData);
        $this->assertEquals('OK', $response['result']['result']);
    }

    public function testTrackingPackage()
    {
        // Dummy data may not always work :)
        $data = [
            "partner_id" => (int)$this->partnerId,
            "data" => [
                [
                    "picking_address" => [
                        "address_reference" => "clt0020"
                    ],
                    "recipient_address" => [
                        "city" => 42,
                        "street" => "Rue  2",
                        "street2" => "Bv Testing address",
                        "name" => "UnitTest via API",
                        "phone" => "+212000000"
                    ],
                    "shipment_type" => "relay_point",
                    "cash_on_delivery" => 1000,
                    "parcel_reference" => "Cl" . microtime(),
                    "barcode" => microtime()
                ]
            ]
        ];

        $response = $this->apiClient->createPackage($data);
        $this->assertEquals('OK', $response['result']['result']);

        $trackingData = [
            "partner_id" => (int)$this->partnerId,
            "parcel_reference" => $response['result']['data'][0]['parcel_reference'],
        ];

        $response = $this->apiClient->trackingPackage( $trackingData );
        $this->assertEquals('OK', $response['result']['result']);
    }

    public function testCreateAddress()
    {
        $data = [
            "partner_id" => (int)$this->partnerId,
            "data" => [
                [
                    "name" => "UnitTest",
                    "address_reference" => "clt".microtime(),
                    "street" => "Unitest street",
                    "street2" => "Unitest street 2",
                    "city" => 42,
                    "phone" => "+21200000000",
                    "type" => "delivery"
                ]
            ]
        ];

        $response = $this->apiClient->createAddress($data);

        $this->assertEquals('OK', $response['result']['result']);
    }

    public function testGetPackageLabel()
    {
        // Dummy data may not always work :)
        $data = [
            "partner_id" => (int)$this->partnerId,
            "data" => [
                [
                    "picking_address" => [
                        "address_reference" => "clt0020"
                    ],
                    "recipient_address" => [
                        "city" => 42,
                        "street" => "Rue  2",
                        "street2" => "Bv Testing address",
                        "name" => "UnitTest via API",
                        "phone" => "+212000000"
                    ],
                    "shipment_type" => "relay_point",
                    "cash_on_delivery" => 1000,
                    "parcel_reference" => "Cl" . microtime(),
                    "barcode" => microtime()
                ]
            ]
        ];

        $response = $this->apiClient->createPackage($data);
        $this->assertEquals('OK', $response['result']['result']);

        $labelData = [
            "partner_id" => (int)$this->partnerId,
            "parcel_references" => [
                $response['result']['data'][0]['parcel_reference']
            ]
        ];

        $response = $this->apiClient->getPackageLabel( $labelData );
        $this->assertEquals('OK', $response['result']['result']);
    }

    public function testGetRates()
    {
        $data = [
            "partner_id" => (int)$this->partnerId,
            "picking_city" => 42,
            "recipient_city" => 42,
            "shipment_type" => "home_delivery"
        ];

        $response = $this->apiClient->getRates($data);
        $this->assertEquals('OK', $response['result']['result']);
    }
}
