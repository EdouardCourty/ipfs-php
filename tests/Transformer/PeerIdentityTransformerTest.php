<?php

declare(strict_types=1);

namespace IPFS\Tests\Transformer;

use IPFS\Transformer\PeerIdentityTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \IPFS\Transformer\PeerIdentityTransformer
 */
class PeerIdentityTransformerTest extends TestCase
{
    private PeerIdentityTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new PeerIdentityTransformer();
    }

    /**
     * @covers ::transform
     */
    public function testItTransforms(): void
    {
        $data = [
            'ID' => 'QmQCU2EcMqAqQPR2i9bChDtGNJchTbq5TbXJJ16u19uLTa',
            'PublicKey' => 'CAASpgIwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCwin2xA7JpMY/vKGHjjupGH7AhJ451wnfhPqG4glnIKFz41NDZa/bQXk05Gw/SeUONsUUbQ5qiBR1WZR4ExzZaipuRqGkPDdgoG2b1gVtFeUharsE5mLNQP6M2mjOGXpLH/tVP8ONe4FkqKLnt9EJ1sIjRr/qxs+uCxheHepCMmzzCnpIwwOqDBhmEQDWDmX4QsosPCdco2TDzLvSJiCXhuMZ6k8MZgt9EfMjpxri7euDgBnw4JFmWFpyfJlDose5z8F84bKd5DBgWdhFObiJUyI9IEv1j7lMobHYJtu9WVLhgkLUYUnt05qLqysPpZHlnmahi8plolCByNeEvPkubAgMBAAE=',
            'Addresses' => [
                '/dns4/ny5.bootstrap.libp2p.io/tcp/443/wss/p2p/QmQCU2EcMqAqQPR2i9bChDtGNJchTbq5TbXJJ16u19uLTa',
            ],
            'AgentVersion' => 'rust-libp2p-server/0.12.3',
            'Protocols' => [
                '/ipfs/id/1.0.0',
            ],
        ];

        $result = $this->transformer->transform($data);

        $this->assertSame($data['ID'], $result->id);
        $this->assertSame($data['PublicKey'], $result->publicKey);
        $this->assertSame($data['Addresses'], $result->addresses);
        $this->assertSame($data['AgentVersion'], $result->agentVersion);
        $this->assertSame($data['Protocols'], $result->protocols);
    }
}
