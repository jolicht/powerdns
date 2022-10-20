<?php

namespace Jolicht\Powerdns\Exception;

abstract class ServerException extends \Exception
{
    public function __construct(
        string $message,
        int $code,
        private readonly array $messages = []
    ) {
        parent::__construct($message, $code);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
