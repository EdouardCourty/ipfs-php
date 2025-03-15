<?php

declare(strict_types=1);

namespace IPFS\Tests\Client;

use IPFS\Client\IPFSClient;
use IPFS\Client\ScopedHttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ipfs\Client\IPFSClient
 */
class IPFSClientTest extends TestCase
{
    private MockObject&ScopedHttpClient $httpClient;
    private IPFSClient $client;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ScopedHttpClient::class);

        $this->client = new IPFSClient();

        $reflection = new \ReflectionClass($this->client);
        $reflection->getProperty('httpClient')->setValue($this->client, $this->httpClient);
    }

    /**
     * @covers ::add
     */
    public function testAddFile(): void
    {
        $content = 'Hello, World!';

        $mockReturn = [
            'Name' => 'hello.txt',
            'Hash' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z1z',
            'Size' => '13',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/add', [
                'body' => [
                    'path' => $content,
                ],
                'query' => [],
                'headers' => [
                    'Content-Type' => 'multipart/form-data',
                ],
            ])
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->add($content);

        $this->assertSame($mockReturn['Name'], $result->name);
        $this->assertSame($mockReturn['Hash'], $result->hash);
        $this->assertSame((int) $mockReturn['Size'], $result->size);
    }

    /**
     * @covers ::cat
     */
    public function testCat(): void
    {
        $mockReturn = 'Hello, World!';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/cat', [
                'query' => [
                    'arg' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                    'offset' => 0,
                    'length' => null,
                ],
            ])
            ->willReturn($mockReturn);

        $result = $this->client->cat('QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z');

        $this->assertSame($mockReturn, $result);
    }

    /**
     * @covers ::get
     */
    public function testGet(): void
    {
        $mockReturn = 'Hello, World!';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/get', [
                'query' => [
                    'arg' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                    'archive' => false,
                    'compress' => false,
                    'compression-level' => null,
                ],
            ])
            ->willReturn($mockReturn);

        $result = $this->client->get('QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z');

        $this->assertSame($mockReturn, $result);
    }

    /**
     * @covers ::list
     */
    public function testList(): void
    {
        $mockReturn = [
            'Objects' => [
                [
                    'Hash' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                    'Links' => [
                        [
                            'Name' => 'hello.txt',
                            'ModTime' => '456789023',
                            'Mode' => 567,
                            'Hash' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                            'Size' => 13,
                            'Type' => 2,
                            'Target' => 'Target',
                        ],
                    ],
                ],
            ],
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/ls', [
                'query' => [
                    'arg' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                    'headers' => true,
                ],
            ])
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->list('QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z');

        $this->assertCount(1, $result);
        $this->assertSame($mockReturn['Objects'][0]['Hash'], $result[0]->hash);
        $this->assertCount(1, $result[0]->links);
        $this->assertSame($mockReturn['Objects'][0]['Links'][0]['Name'], $result[0]->links[0]->name);
        $this->assertSame($mockReturn['Objects'][0]['Links'][0]['Hash'], $result[0]->links[0]->hash);
        $this->assertSame($mockReturn['Objects'][0]['Links'][0]['Size'], $result[0]->links[0]->size);
        $this->assertSame($mockReturn['Objects'][0]['Links'][0]['Type'], $result[0]->links[0]->type);
    }

    /**
     * @covers ::getNode
     */
    public function testGetNode(): void
    {
        $mockReturn = [
            'ID' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
            'PublicKey' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
            'Addresses' => [
                '/ip4/',
                '/ip6/',
            ],
            'AgentVersion' => 'go-ipfs/0.4.23',
            'Protocols' => [
                '/floodsub/1.0.0',
                '/ipfs/bitswap/1.2.0',
            ],
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/id', [
                'query' => [
                    'arg' => null,
                ],
            ])
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->getNode();

        $this->assertSame($mockReturn['ID'], $result->id);
        $this->assertSame($mockReturn['PublicKey'], $result->publicKey);
        $this->assertCount(2, $result->addresses);
        $this->assertSame($mockReturn['Addresses'][0], $result->addresses[0]);
        $this->assertSame($mockReturn['Addresses'][1], $result->addresses[1]);
        $this->assertSame($mockReturn['AgentVersion'], $result->agentVersion);
        $this->assertCount(2, $result->protocols);
        $this->assertSame($mockReturn['Protocols'][0], $result->protocols[0]);
        $this->assertSame($mockReturn['Protocols'][1], $result->protocols[1]);
    }

    /**
     * @covers ::pin
     */
    public function testPin(): void
    {
        $mockReturn = [
            'Pins' => [
                'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
            ],
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/pin/add', [
                'query' => [
                    'arg' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                    'recursive' => false,
                    'name' => null,
                ],
            ])
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->pin('QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z');

        $this->assertCount(1, $result);
        $this->assertSame($mockReturn['Pins'][0], $result[0]);
    }

    /**
     * @covers ::unpin
     */
    public function testUnpin(): void
    {
        $mockReturn = [
            'Pins' => [
                'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
            ],
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/pin/rm', [
                'query' => [
                    'arg' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                    'recursive' => false,
                ],
            ])
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->unpin('QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z');

        $this->assertCount(1, $result);
        $this->assertSame($mockReturn['Pins'][0], $result[0]);
    }

    /**
     * @covers ::ping
     */
    public function testPing(): void
    {
        $mockReturn = [
            'Success' => true,
            'Time' => '1234567890',
            'Text' => 'Hello, World!',
        ];
        $jsonEncoded = json_encode($mockReturn);
        // Ping responses contain multiple JSON objects separated by newlines.
        $actualMockReturn = implode("\n", [$jsonEncoded, $jsonEncoded, $jsonEncoded]);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/ping', [
                'query' => [
                    'arg' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
                    'count' => 10,
                ],
            ])
            ->willReturn($actualMockReturn);

        $result = $this->client->ping('QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z');

        $this->assertSame($mockReturn['Success'], $result->success);
        $this->assertSame($mockReturn['Time'], $result->time);
        $this->assertSame($mockReturn['Text'], $result->text);
    }

    /**
     * @covers ::version
     */
    public function testVersion(): void
    {
        $mockReturn = [
            'Version' => '0.4.23',
            'Commit' => 'c3b3f4a',
            'Repo' => 'ipfs/go-ipfs',
            'System' => 'linux',
            'Golang' => 'go1.12.1',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/version')
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->version();

        $this->assertSame($mockReturn['Version'], $result->version);
        $this->assertSame($mockReturn['Commit'], $result->commit);
        $this->assertSame($mockReturn['Repo'], $result->repo);
        $this->assertSame($mockReturn['System'], $result->system);
        $this->assertSame($mockReturn['Golang'], $result->golang);
    }

    /**
     * @covers ::shutdown
     */
    public function testShutdown(): void
    {
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/shutdown');

        $this->client->shutdown();
    }

    /**
     * @covers ::getConfiguration
     */
    public function testGetConfiguration(): void
    {
        $mockReturn = [
            'Whatever' => 'you want',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/config/show')
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->getConfiguration();

        $this->assertSame($mockReturn, $result);
    }
}
