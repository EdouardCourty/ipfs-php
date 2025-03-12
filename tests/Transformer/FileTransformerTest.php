<?php

declare(strict_types=1);

namespace IPFS\Tests\Transformer;

use IPFS\Transformer\FileTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ipfs\Transformer\FileTransformer
 */
class FileTransformerTest extends TestCase
{
    private FileTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new FileTransformer();
    }

    /**
     * @covers ::transform
     * @covers ::assertParameters
     */
    public function testItTransforms(): void
    {
        $data = [
            'Name' => 'FileName',
            'Hash' => 'FileHash',
            'Size' => 123456789,
        ];

        $result = $this->transformer->transform($data);

        $this->assertSame($data['Name'], $result->name);
        $this->assertSame($data['Hash'], $result->hash);
        $this->assertSame($data['Size'], $result->size);
    }
}
