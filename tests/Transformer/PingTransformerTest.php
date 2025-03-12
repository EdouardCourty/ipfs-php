<?php

declare(strict_types=1);

namespace Ipfs\Tests\Transformer;

use Ipfs\Transformer\PingTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ipfs\Transformer\PingTransformer
 */
class PingTransformerTest extends TestCase
{
    private PingTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new PingTransformer();
    }

    /**
     * @covers ::transform
     * @covers ::assertParameters
     */
    public function testItTransforms(): void
    {
        $data = [
            'Success' => true,
            'Text' => 'Hello, world!',
            'Time' => '2021-10-01T00:00:00Z',
        ];

        $result = $this->transformer->transform($data);

        $this->assertSame($data['Success'], $result->success);
        $this->assertSame($data['Text'], $result->text);
        $this->assertSame($data['Time'], $result->time);
    }
}
