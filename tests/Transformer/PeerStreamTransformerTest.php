<?php

declare(strict_types=1);

namespace IPFS\Tests\Transformer;

use IPFS\Transformer\PeerStreamTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \IPFS\Transformer\PeerStreamTransformer
 */
class PeerStreamTransformerTest extends TestCase
{
    private PeerStreamTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new PeerStreamTransformer();
    }

    /**
     * @covers ::transform
     */
    public function testItTransforms(): void
    {
        $data = [
            'Protocol' => 'protocol/v1',
        ];

        $result = $this->transformer->transform($data);

        $this->assertSame($data['Protocol'], $result->protocol);
    }
}
