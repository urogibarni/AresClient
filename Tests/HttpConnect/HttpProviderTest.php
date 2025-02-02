<?php

declare(strict_types = 1);

namespace Tests\HttpConnect;

use Ares\CompanyDataFetcher\HttpConnect\HttpProvider;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpProviderTest extends TestCase
{
    public function testZee(): void
    {
        $client = $this->createMock(Client::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $provider = new HttpProvider($client);

        $stream
            ->expects(self::once())
            ->method('getContents')
            ->willReturn('test content')
        ;

        $response
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($stream)
        ;

        $client
            ->expects(self::once())
            ->method('request')
            ->willReturn($response)
        ;

        $provider->request('get', 'test-uri');

        $this->assertInstanceOf(ResponseInterface::class, $provider->getResponse());
        $this->assertEquals('test content', $provider->getContent());
    }
}
