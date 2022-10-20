<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Api;

use InvalidArgumentException;

use function is_array;

use Jolicht\Powerdns\Exception\ExceptionFactory;
use Jolicht\Powerdns\Model\Zone;
use Jolicht\Powerdns\ValueObject\ZoneId;

use function json_decode;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpGetZone implements GetZone
{
    public function __construct(
        private readonly HttpClientInterface $client
    ) {
    }

    public function __invoke(ZoneId $id): Zone
    {
        $response = $this->client->request('GET', 'zones/'.$id->toString());
        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw ExceptionFactory::fromResponse($response);
        }

        $content = $response->getContent(false);
        $zoneData = json_decode($content, true);

        if (!is_array($zoneData)) {
            throw new InvalidArgumentException('Cannot decode zone data');
        }

        return Zone::fromArray($zoneData);
    }
}
