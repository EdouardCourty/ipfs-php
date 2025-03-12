<?php

declare(strict_types=1);

namespace Ipfs\Tests\Transformer;

use Ipfs\Transformer\FileLinkTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ipfs\Transformer\FileLinkTransformer
 */
class FileLinkTransformerTest extends TestCase
{
    private FileLinkTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new FileLinkTransformer();
    }

    /**
     * @covers ::transform
     * @covers ::assertParameters
     */
    public function testItTransforms(): void
    {
        $data = [
            'Hash' => 'LinkHash',
            'ModTime' => '1741792497',
            'Mode' => 12,
            'Name' => 'Name',
            'Size' => 321,
            'Target' => 'Target',
            'Type' => 69,
        ];

        $result = $this->transformer->transform($data);

        $this->assertSame($data['Hash'], $result->hash);
        $this->assertSame($data['ModTime'], $result->modTime);
        $this->assertSame($data['Mode'], $result->mode);
        $this->assertSame($data['Name'], $result->name);
        $this->assertSame($data['Size'], $result->size);
        $this->assertSame($data['Target'], $result->target);
        $this->assertSame($data['Type'], $result->type);
    }
}
