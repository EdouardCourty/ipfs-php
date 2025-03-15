<?php

declare(strict_types=1);

namespace IPFS\Tests\Transformer;

use IPFS\Transformer\PeerIdentityTransformer;
use IPFS\Transformer\PeerStreamTransformer;
use IPFS\Transformer\PeerTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \IPFS\Transformer\PeerTransformer
 */
class PeerTransformerTest extends TestCase
{
    private PeerTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new PeerTransformer(
            peerIdentityTransformer: new PeerIdentityTransformer(),
            peerStreamTransformer: new PeerStreamTransformer(),
        );
    }

    /**
     * @covers ::transform
     */
    public function testItTransforms(): void
    {
        $data = [
            'Addr' => '/ip4/139.178.65.157/udp/4001/quic-v1',
            'Peer' => 'QmQCU2EcMqAqQPR2i9bChDtGNJchTbq5TbXJJ16u19uLTa',
            'Identify' => [
                'ID' => '',
                'PublicKey' => '',
                'Addresses' => null,
                'AgentVersion' => '',
                'Protocols' => null,
            ],
        ];

        $peer = $this->transformer->transform($data);

        $this->assertSame($data['Addr'], $peer->address);
        $this->assertSame($data['Peer'], $peer->identifier);
        $this->assertNull($peer->direction);
        $this->assertNull($peer->latency);
        $this->assertEmpty($peer->streams);

        $this->assertNull($peer->identity);
    }

    public function testItTransformsWithVerboseData(): void
    {
        $data = [
            'Addr' => '/ip4/139.178.65.157/udp/4001/quic-v1',
            'Peer' => 'QmQCU2EcMqAqQPR2i9bChDtGNJchTbq5TbXJJ16u19uLTa',
            'Latency' => '130.549486ms',
            'Direction' => 2,
            'Streams' => [
                [
                    'Protocol' => '/ipfs/kad/1.0.0',
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
                    '/ipfs/id/1.0.0',
                ],
            ],
        ];

        $peer = $this->transformer->transform($data);

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
}
