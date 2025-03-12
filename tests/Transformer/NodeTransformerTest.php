<?php

declare(strict_types=1);

namespace Ipfs\Tests\Transformer;

use Ipfs\Transformer\NodeTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ipfs\Transformer\NodeTransformer
 */
class NodeTransformerTest extends TestCase
{
    private NodeTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new NodeTransformer();
    }

    /**
     * @covers ::transform
     * @covers ::assertParameters
     */
    public function testItTransforms(): void
    {
        $data = [
            'ID' => 'QmQ9Q1',
            'PublicKey' => 'PublicKey',
            'Addresses' => [
                'Address1',
                'Address2',
            ],
            'AgentVersion' => '1.0.0',
            'Protocols' => [
                'Protocol1',
                'Protocol2',
            ],
        ];

        $transformed = $this->transformer->transform($data);

        $this->assertSame($data['ID'], $transformed->id);
        $this->assertSame($data['PublicKey'], $transformed->publicKey);
        $this->assertSame($data['Addresses'], $transformed->addresses);
        $this->assertSame($data['AgentVersion'], $transformed->agentVersion);
        $this->assertSame($data['Protocols'], $transformed->protocols);
    }
}
