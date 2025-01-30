<?php

declare(strict_types = 1);

namespace App\Http\Controllers\AresClient\HttpConnect\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\ClientTrait;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * @deprecated This method is deprecated and is only for demonstration purposes.
 */
class FileGetContentsClient implements ClientInterface
{
    use ClientTrait;

    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        $content = file_get_contents($uri);

        if ($content === false) {
            throw new TransferException(sprintf(
                'file_get_contents: Error fetching content from %s. Please check the URL and try again.', $uri
                )
            );
        }

        return new Response(body: $content);
    }

    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        throw new RuntimeException('FileGetContentsClient does not support the requestAsync method.');
    }

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        throw new RuntimeException('FileGetContentsClient does not support the send method.');
    }

    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        throw new RuntimeException('FileGetContentsClient does not support the sendAsync method.');
    }

    public function getConfig(?string $option = null)
    {
        throw new RuntimeException('FileGetContentsClient does not support the getConfig method.');
    }
}

