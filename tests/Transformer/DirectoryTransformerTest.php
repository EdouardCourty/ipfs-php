<?php

declare(strict_types=1);

namespace IPFS\Tests\Transformer;

use IPFS\Transformer\DirectoryTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \IPFS\Transformer\DirectoryTransformer
 */
class DirectoryTransformerTest extends TestCase
{
    private DirectoryTransformer $directoryTransformer;

    protected function setUp(): void
    {
        $this->directoryTransformer = new DirectoryTransformer();
    }

    /**
     * @covers ::transform
     */
    public function testItTransforms(): void
    {
        $data = [
            'Name' => 'Directory name',
            'Hash' => 'NiceHash',
            'Size' => '123456',
        ];

        $result = $this->directoryTransformer->transform($data);

        $this->assertSame($data['Name'], $result->name);
        $this->assertSame($data['Hash'], $result->hash);
        $this->assertSame($data['Size'], $result->size);

        $this->assertEmpty($result->files);
    }
}
