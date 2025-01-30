<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\HttpConnect;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\MessageInterface;

class HttpProvider
{
    private MessageInterface $response;

    public function __construct(private readonly ClientInterface $httpClient)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $method, $uri, array $options = []): void
    {
        $this->response = $this->httpClient->request($method, $uri, $options);
    }

    public function getResponse(): MessageInterface
    {
        return $this->response;
    }

    public function getContent(): string
    {
        return $this->response->getBody()->getContents();
    }
}
