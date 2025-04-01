<?php

declare(strict_types=1);

namespace IPFS\Tests\Client;

use IPFS\Client\IPFSClient;
use IPFS\Client\ScopedHttpClient;
use IPFS\Model\Peer;
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
    public function testAdd(): void
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
     * @covers ::addFile
     */
    public function testAddFile(): void
    {
        $mockReturn = [
            'Name' => 'hello.txt',
            'Hash' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z1z',
            'Size' => '13',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/add', $this->anything())
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->addFile('./tests/Client/IPFSClientTest.php');

        $this->assertSame($mockReturn['Name'], $result->name);
        $this->assertSame($mockReturn['Hash'], $result->hash);
        $this->assertSame((int) $mockReturn['Size'], $result->size);
    }

    /**
     * @covers ::addFile
     */
    public function testCannotAddFileThatDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->client->addFile('/path/to/non/existent/file');
    }

    /**
     * @covers ::addDirectory
     */
    public function testAddDirectory(): void
    {
        $directoryReturn = [
            'Name' => 'Directory name',
            'Hash' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
            'Size' => '999999999999', // Should be bigger than the files sizes
        ];
        $mockReturn = [
            'Name' => 'File name',
            'Hash' => 'QmZ4tDuvese8GKQ3vz8Fq8bKz1q3z1z1z1z1z1z1z1z1z',
            'Size' => '1726312',
        ];
        $jsonEncoded = json_encode($mockReturn);
        $jsonEncodedDIrectoryPayload = json_encode($directoryReturn);
        // wrap-with-directory responses contain multiple JSON objects separated by newlines.
        $actualMockReturn = implode("\n", [$jsonEncoded, $jsonEncoded, $jsonEncoded, $jsonEncodedDIrectoryPayload]);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/add', $this->anything())
            ->willReturn($actualMockReturn);

        $result = $this->client->addDirectory('./tests');

        $this->assertSame($directoryReturn['Name'], $result->name);
        $this->assertSame($directoryReturn['Hash'], $result->hash);
        $this->assertSame($directoryReturn['Size'], $result->size);

        $this->assertCount(3, $result->files);
    }

    /**
     * @covers ::addDirectory
     */
    public function testCannotaddDirectoryThatDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->client->addDirectory('/path/to/non/existent/folder');
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

    /**
     * @covers ::getPeers
     */
    public function testGetPeers(): void
    {
        $mockReturn = [
            'Peers' => [
                [
                    'Addr' => '/ip4/139.178.65.157/udp/4001/quic-v1',
                    'Peer' => 'QmaCpDMGvV2BGHeYERUEnRQAwe3N8SzbUtfsmvsqQLuvuJ',
                    'Latency' => '130.549486ms',
                    'Direction' => 2,
                    'Streams' => [
                        [
                            'Protocol' => '/ipfs/kad/1.0.0',
                        ],
                    ],
                    'Identify' => [
                        'ID' => 'QmaCpDMGvV2BGHeYERUEnRQAwe3N8SzbUtfsmvsqQLuvuJ',
                        'PublicKey' => 'CAASpgIwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCwin2xA7JpMY/vKGHjjupGH7AhJ451wnfhPqG4glnIKFz41NDZa/bQXk05Gw/SeUONsUUbQ5qiBR1WZR4ExzZaipuRqGkPDdgoG2b1gVtFeUharsE5mLNQP6M2mjOGXpLH/tVP8ONe4FkqKLnt9EJ1sIjRr/qxs+uCxheHepCMmzzCnpIwwOqDBhmEQDWDmX4QsosPCdco2TDzLvSJiCXhuMZ6k8MZgt9EfMjpxri7euDgBnw4JFmWFpyfJlDose5z8F84bKd5DBgWdhFObiJUyI9IEv1j7lMobHYJtu9WVLhgkLUYUnt05qLqysPpZHlnmahi8plolCByNeEvPkubAgMBAAE=',
                        'Addresses' => [
                            '/dns4/ny5.bootstrap.libp2p.io/tcp/443/wss/p2p/QmaCpDMGvV2BGHeYERUEnRQAwe3N8SzbUtfsmvsqQLuvuJ',
                        ],
                        'AgentVersion' => 'rust-libp1p-server/0.12.3',
                        'Protocols' => [
                            '/ipfs/id/1.0.0',
                        ],
                    ],
                ],
                [
                    'Addr' => '/ip4/139.178.65.157/udp/4001/quic-v1',
                    'Peer' => 'QmQCU2EcMqAqQPR2i9bChDtGNJchTbq5TbXJJ16u19uLTa',
                    'Latency' => '130.549486ms',
                    'Direction' => 2,
                    'Streams' => [
                        [
                            'Protocol' => '/ipfs/dahk/1.0.0',
                        ],
                    ],
                    'Identify' => [
                        'ID' => 'QmQCU2EcMqAqQPR2i9bChDtGNJchTbq5TbXJJ16u19uLTa',
                        'PublicKey' => 'CAASpgIwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCwin2xA7JpMY/vKGHjjupGH7AhJ451wnfhPqG4glnIKFz41NDZa/bQXk05Gw/SeUONsUUbQ5qiBR1WZR4ExzZaipuRqGkPDdgoG2b1gVtFeUharsE5mLNQP6M2mjOGXpLH/tVP8ONe4FkqKLnt9EJ1sIjRr/qxs+uCxheHepCMmzzCnpIwwOqDBhmEQDWDmX4QsosPCdco2TDzLvSJiCXhuMZ6k8MZgt9EfMjpxri7euDgBnw4JFmWFpyfJlDose5z8F84bKd5DBgWdhFObiJUyI9IEv1j7lMobHYJtu9WVLhgkLUYUnt05qLqysPpZHlnmahi8plolCByNeEvPkubAgMBAAE=',
                        'Addresses' => [
                            '/dns4/ny5.bootstrap.libp2p.io/tcp/443/wss/p2p/QmQCU2EcMqAqQPR2i9bChDtGNJchTbq5TbXJJ16u19uLTa',
                        ],
                        'AgentVersion' => 'rust-libp2p-server/0.12.3',
                        'Protocols' => [
                            '/ipfs/id/2.0.0',
                        ],
                    ],
                ],
            ],
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/swarm/peers')
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->getPeers();

        $this->assertCount(\count($mockReturn['Peers']), $result);

        foreach ($result as $key => $peer) {
            $correspondingMockData = $mockReturn['Peers'][$key];
            $this->assertPeerResponse($correspondingMockData, $peer);
        }
    }

    private function assertPeerResponse(array $data, Peer $peer): void
    {
        $this->assertSame($data['Addr'], $peer->address);
        $this->assertSame($data['Peer'], $peer->identifier);
        $this->assertSame($data['Direction'], $peer->direction);
        $this->assertSame((float) $data['Latency'], $peer->latency);

        foreach ($peer->streams as $key => $stream) {
            $correspondingResponseStream = $data['Streams'][$key];

            $this->assertSame($stream->protocol, $correspondingResponseStream['Protocol']);
        }

        $identity = $peer->identity;
        $this->assertNotNull($identity);

        $this->assertSame($data['Identify']['ID'], $identity->id);
        $this->assertSame($data['Identify']['AgentVersion'], $identity->agentVersion);
        $this->assertSame($data['Identify']['PublicKey'], $identity->publicKey);
        $this->assertSame($data['Identify']['Addresses'], $identity->addresses);
        $this->assertSame($data['Identify']['Protocols'], $identity->protocols);
    }

    /**
     * @covers ::resolve
     */
    public function testResolve(): void
    {
        $mockReturn = [
            'Path' => '/ipfs/QmSnuWmxptJZdLJpKRarxBMS2Ju2oANVrgbr2xWbie9b2D',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/api/v0/resolve', [
                'query' => [
                    'arg' => 'QmSnuWmxptJZdLJpKRarxBMS2Ju2oANVrgbr2xWbie9b2D',
                    'recursive' => true,
                ],
            ])
            ->willReturn(json_encode($mockReturn));

        $result = $this->client->resolve('QmSnuWmxptJZdLJpKRarxBMS2Ju2oANVrgbr2xWbie9b2D');

        $this->assertSame($mockReturn['Path'], $result);
    }
}
