<?php

namespace Jolicht\Powerdns\Tests\Unit\Exception;

use Jolicht\Powerdns\Exception\BadRequestException;
use Jolicht\Powerdns\Exception\ExceptionFactory;
use Jolicht\Powerdns\Exception\InternalServerErrorException;
use Jolicht\Powerdns\Exception\NotFoundException;
use Jolicht\Powerdns\Exception\UndefinedException;
use Jolicht\Powerdns\Exception\UnprocessableEntityException;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \Jolicht\Powerdns\Exception\ExceptionFactory
 */
class ExceptionFactoryTest extends TestCase
{
    private ResponseInterface|Stub $response;

    protected function setUp(): void
    {
        $this->response = $this->createStub(ResponseInterface::class);
    }

    public function testFromResponseErrorCodeIsResponseStatusCode(): void
    {
        $content = [
            'error' => 'test error message',
        ];

        $this->response
            ->method('getContent')
            ->willReturn(json_encode($content));

        $this->response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_LOCKED);

        $exception = ExceptionFactory::fromResponse($this->response);
        $this->assertSame(Response::HTTP_LOCKED, $exception->getCode());
    }

    public function testFromResponseHasErrorReturnsExceptionWithErrorMessage(): void
    {
        $content = [
            'error' => 'test error message',
        ];

        $this->response
            ->method('getContent')
            ->willReturn(json_encode($content));

        $this->response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_LOCKED);

        $exception = ExceptionFactory::fromResponse($this->response);
        $this->assertSame('test error message', $exception->getMessage());
    }

    public function testFromResponseHasAdditionalErrorsReturnsExceptionWithAdditionalErrorMessages(): void
    {
        $content = [
            'error' => 'test error message',
            'errors' => [
                'error1',
                'error2',
            ],
        ];

        $this->response
            ->method('getContent')
            ->willReturn(json_encode($content));

        $this->response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_LOCKED);

        $exception = ExceptionFactory::fromResponse($this->response);
        $this->assertSame([
            'error1',
            'error2',
        ], $exception->getMessages());
    }

    public function testFromResponseDoesNotHaveErrorReturnsExceptionWithDefaultMessage(): void
    {
        $content = [];

        $this->response
            ->method('getContent')
            ->willReturn(json_encode($content));

        $this->response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_LOCKED);

        $exception = ExceptionFactory::fromResponse($this->response);
        $this->assertSame('Undefined error occurred.', $exception->getMessage());
    }

    public function testFromResponseContentNotDecodableReturnsExceptionWithDefaultMessage(): void
    {
        $this->response
            ->method('getContent')
            ->willReturn('{invalid');

        $this->response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_LOCKED);

        $exception = ExceptionFactory::fromResponse($this->response);
        $this->assertSame('Undefined error occurred.', $exception->getMessage());
    }

    /**
     * @dataProvider exceptionByStatusCodeDataProvider
     */
    public function testFromResponseReturnsExceptionTypeByHttpStatusCode(int $httpStatusCode, string $expectedException): void
    {
        $this->response
            ->method('getContent')
            ->willReturn('[]');

        $this->response
            ->method('getStatusCode')
            ->willReturn($httpStatusCode);

        $exception = ExceptionFactory::fromResponse($this->response);
        $this->assertInstanceOf($expectedException, $exception);
    }

    private function exceptionByStatusCodeDataProvider(): array
    {
        return [
            [Response::HTTP_BAD_REQUEST, BadRequestException::class],
            [Response::HTTP_NOT_FOUND, NotFoundException::class],
            [Response::HTTP_NOT_FOUND, NotFoundException::class],
            [Response::HTTP_UNPROCESSABLE_ENTITY, UnprocessableEntityException::class],
            [Response::HTTP_INTERNAL_SERVER_ERROR, InternalServerErrorException::class],
            [-5, UndefinedException::class],
        ];
    }
}
