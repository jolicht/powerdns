<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Api;

use InvalidArgumentException;

use function is_array;

use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Exception\ExceptionFactory;
use Jolicht\Powerdns\Model\Zone;

use function json_decode;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpCreateZone implements CreateZone
{
    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function __invoke(CreateZoneDto $createZoneDto): Zone
    {
        $response = $this->httpClient->request('POST', 'zones', [
            'json' => $createZoneDto->jsonSerialize(),
        ]);

        $content = $response->getContent(false);
        $zoneData = json_decode($content, true);

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw ExceptionFactory::fromResponse($response);
        }

        if (!is_array($zoneData)) {
            throw new InvalidArgumentException('Cannot decode zone data');
        }

        return Zone::fromArray($zoneData);
    }
}
