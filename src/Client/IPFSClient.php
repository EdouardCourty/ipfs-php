<?php

declare(strict_types=1);

namespace IPFS\Client;

use IPFS\Model\File;
use IPFS\Model\Node;
use IPFS\Model\Ping;
use IPFS\Model\Version;
use IPFS\Transformer\FileLinkTransformer;
use IPFS\Transformer\FileListTransformer;
use IPFS\Transformer\FileTransformer;
use IPFS\Transformer\NodeTransformer;
use IPFS\Transformer\PingTransformer;
use IPFS\Transformer\VersionTransformer;

class IPFSClient
{
    public const int DEFAULT_PORT = 5001;
    public const string DEFAULT_HOST = 'localhost';
    public const string DEFAULT_PROTOCOL = 'http';

    public const int DEFAULT_TIMEOUT = 5;

    private ScopedHttpClient $httpClient;

    public function __construct(
        ?string $url = null,
        ?string $host = self::DEFAULT_HOST,
        ?int $port = self::DEFAULT_PORT,
        string $protocol = self::DEFAULT_PROTOCOL,
        int $timeout = self::DEFAULT_TIMEOUT,
    ) {
        $baseUrl = $url === null
            ? "{$protocol}://{$host}:{$port}"
            : $url;

        $this->httpClient = new ScopedHttpClient($baseUrl, $timeout);
    }

    public function add(string $file, array $parameters = []): File
    {
        $response = $this->httpClient->request('POST', '/api/v0/add', [
            'body' => [
                'path' => $file,
            ],
            'query' => $parameters,
            'headers' => [
                'Content-Type' => 'multipart/form-data',
            ],
        ]);

        $parsedResponse = json_decode($response, true);

        $transformer = new FileTransformer();
        return $transformer->transform($parsedResponse);
    }

    public function cat(string $hash, int $offset = 0, ?int $length = null): string
    {
        return $this->httpClient->request('POST', '/api/v0/cat', [
            'query' => [
                'arg' => $hash,
                'offset' => $offset,
                'length' => $length,
            ],
        ]);
    }

    public function get(string $hash, bool $archive = false, bool $compress = false, int $compressionLevel = null): string
    {
        return $this->httpClient->request('POST', '/api/v0/get', [
            'query' => [
                'arg' => $hash,
                'archive' => $archive,
                'compress' => $compress,
                'compression-level' => $compressionLevel,
            ],
        ]);
    }

    public function list(string $hash): array
    {
        $response = $this->httpClient->request('POST', '/api/v0/ls', [
            'query' => [
                'arg' => $hash,
                'headers' => true,
            ],
        ]);

        $parsedResponse = json_decode($response, true);

        $transformer = new FileListTransformer(new FileLinkTransformer());
        return $transformer->transform($parsedResponse);
    }

    public function getNode(?string $nodeId = null): Node
    {
        $response = $this->httpClient->request('POST', '/api/v0/id', [
            'query' => [
                'arg' => $nodeId,
            ],
        ]);

        $parsedResponse = json_decode($response, true);

        $nodeTransformer = new NodeTransformer();
        return $nodeTransformer->transform($parsedResponse);
    }

    public function pin(string $path, bool $recursive = false, ?string $name = null): array
    {
        $response = $this->httpClient->request('POST', '/api/v0/pin/add', [
            'query' => [
                'arg' => $path,
                'recursive' => $recursive,
                'name' => $name,
            ],
        ]);

        $parsedResponse = json_decode($response, true);

        return $parsedResponse['Pins'] ?? [];
    }

    public function unpin(string $path, bool $recursive = false): array
    {
        $response = $this->httpClient->request('POST', '/api/v0/pin/rm', [
            'query' => [
                'arg' => $path,
                'recursive' => $recursive,
            ],
        ]);

        $parsedResponse = json_decode($response, true);

        return $parsedResponse['Pins'] ?? [];
    }

    public function ping(string $nodeId, int $count = 10): Ping
    {
        $response = $this->httpClient->request('POST', '/api/v0/ping', [
            'query' => [
                'arg' => $nodeId,
                'count' => $count,
            ],
        ]);

        $parsedResponse = json_decode($response, true);

        $pingTransformer = new PingTransformer();
        return $pingTransformer->transform($parsedResponse);
    }

    public function version(): Version
    {
        $response = $this->httpClient->request('POST', '/api/v0/version');

        $parsedResponse = json_decode($response, true);

        $versionTransformer = new VersionTransformer();
        return $versionTransformer->transform($parsedResponse);
    }

    public function shutdown(): void
    {
        $this->httpClient->request('POST', '/api/v0/shutdown');
    }

    public function getConfiguration(): array
    {
        $response = $this->httpClient->request('POST', '/api/v0/config/show');

        return json_decode($response, true);
    }
}
