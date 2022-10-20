<?php

namespace Jolicht\Powerdns\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ExceptionFactory
{
    public static function fromResponse(ResponseInterface $response): ServerException
    {
        $content = $response->getContent(false);

        $data = (array) json_decode($content, true);
        if (!isset($data['error'])) {
            $message = 'Undefined error occurred.';
        } else {
            $message = (string) $data['error'];
        }

        if (isset($data['errors']) && is_array($data['errors'])) {
            $messages = $data['errors'];
        } else {
            $messages = [];
        }

        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case Response::HTTP_BAD_REQUEST:
                return new BadRequestException($message, $statusCode, $messages);
            case Response::HTTP_NOT_FOUND:
                return new NotFoundException($message, $statusCode, $messages);
            case Response::HTTP_UNPROCESSABLE_ENTITY:
                return new UnprocessableEntityException($message, $statusCode, $messages);
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                return new InternalServerErrorException($message, $statusCode, $messages);
            default:
                return new UndefinedException($message, $statusCode, $messages);
        }
    }
}
