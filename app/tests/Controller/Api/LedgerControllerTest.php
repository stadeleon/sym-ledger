<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class LedgerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateLedgerSuccess(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/api/ledgers',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['initialCurrency' => 'USD'])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_CREATED);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('initialCurrency', $responseData);
        $this->assertArrayHasKey('createdAt', $responseData);
        $this->assertEquals('USD', $responseData['initialCurrency']);
    }

    public function testCreateLedgerWrongValue(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/api/ledgers',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['initialCurrency' => 'ZXC'])
        );

        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_BAD_REQUEST);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('errors', $responseData);
    }
}
