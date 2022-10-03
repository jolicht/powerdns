<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Api;

use function in_array;

use InvalidArgumentException;
use Jolicht\Powerdns\ValueObject\HttpStatusCode;
use Jolicht\Powerdns\ValueObject\ZoneId;
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

        if (!in_array($response->getStatusCode(), [HttpStatusCode::HTTP_NO_CONTENT->value, HttpStatusCode::HTTP_NOT_FOUND->value])) {
            throw new InvalidArgumentException('Cannot delete zone');
        }
    }
}
