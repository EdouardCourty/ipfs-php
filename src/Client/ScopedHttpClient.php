<?php

declare(strict_types=1);

namespace IPFS\Client;

use IPFS\Exception\IPFSTransportException;
use IPFS\Exception\JsonException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @internal
 */
class ScopedHttpClient
{
    private HttpClientInterface $httpClient;

    public function __construct(string $url, int $timeout)
    {
        $this->httpClient = HttpClient::createForBaseUri($url, [
            'timeout' => $timeout,
        ]);
    }

    public function request(string $method, string $url, array $parameters = []): string
    {
        $response = $this->httpClient->request($method, $url, $parameters);
        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            return $response->getContent();
        }

        $exceptionMessage = $response->getContent(false);

        try {
            // In some cases, an exception message is returned in JSON format.
            $exceptionMessage = json_decode($exceptionMessage, true, 512, \JSON_THROW_ON_ERROR)['Message'];
        } catch (\Throwable $exception) {
            throw new JsonException(message: 'JSON deserialization error.', previous: $exception);
        }

        match ($statusCode) {
            400 => throw new IPFSTransportException('Malformed request: ' . $exceptionMessage, $statusCode),
            403 => throw new IPFSTransportException('RPC call forbidden: ' . $exceptionMessage, $statusCode),
            404 => throw new IPFSTransportException('RPC endpoint not found: ' . $exceptionMessage, $statusCode),
            500 => throw new IPFSTransportException('Internal server error: ' . $exceptionMessage, $statusCode),
            default => throw new \Exception('Unknown error', $statusCode),
        };
    }
}
