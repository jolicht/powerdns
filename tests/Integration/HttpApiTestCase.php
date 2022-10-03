<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Tests\Integration;

use function getenv;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class HttpApiTestCase extends TestCase
{
    protected HttpClientInterface $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = HttpClient::create([
            'base_uri' => getenv('powerdns_base_uri'),

            'headers' => [
                'X-API-Key' => getenv('powerdns_api_key'),
            ],
        ]);
    }
}
