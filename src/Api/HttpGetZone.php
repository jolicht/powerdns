<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Api;

use InvalidArgumentException;

use function is_array;

use Jolicht\PowerdnsBundle\Model\Zone;
use Jolicht\PowerdnsBundle\ValueObject\ZoneId;

use function json_decode;

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

        $content = $response->getContent(false);
        $zoneData = json_decode($content, true);

        if (!is_array($zoneData)) {
            throw new InvalidArgumentException('Cannot decode zone data');
        }

        return Zone::fromArray($zoneData);
    }
}
