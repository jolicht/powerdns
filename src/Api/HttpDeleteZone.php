<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Api;

use Jolicht\Powerdns\Exception\ExceptionFactory;
use Jolicht\Powerdns\ValueObject\ZoneId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpDeleteZone implements DeleteZone
{
    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function __invoke(ZoneId $zonedId): void
    {
        $response = $this->httpClient->request('DELETE', 'zones/'.$zonedId->toString());

        if (Response::HTTP_NO_CONTENT !== $response->getStatusCode()) {
            throw ExceptionFactory::fromResponse($response);
        }
    }
}
