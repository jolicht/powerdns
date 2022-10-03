<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\ValueObject;

enum HttpStatusCode: int
{
    case HTTP_NO_CONTENT = 204;
    case HTTP_NOT_FOUND = 404;
}
