<?php

declare(strict_types=1);

namespace Ipfs\Tests\Transformer;

use Ipfs\Transformer\FileLinkTransformer;
use Ipfs\Transformer\FileListTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ipfs\Transformer\FileListTransformer
 */
class FileListTransformerTest extends TestCase
{
    private FileListTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new FileListTransformer(new FileLInkTransformer());
    }

    /**
     * @covers ::transform
     * @covers ::assertParameters
     */
    public function testItTransforms(): void
    {
        $data = [
            'Objects' => [
                [
                    'Hash' => 'QmHash',
                    'Links' => [
                        [
                            'Hash' => 'LinkHash',
                            'ModTime' => '1741792497',
                            'Mode' => 12,
                            'Name' => 'Name',
                            'Size' => 321,
                            'Target' => 'Target',
                            'Type' => 69,
                        ],
                        [
                            'Hash' => 'HsahKnil',
                            'ModTime' => '1741792590',
                            'Mode' => 21,
                            'Name' => 'Enam',
                            'Size' => 123,
                            'Target' => 'Tegrat',
                            'Type' => 96,
                        ],
                    ],
                ], [
                    'Hash' => 'QmHash2',
                    'Links' => [],
                ],
            ],
        ];

        $transformed = $this->transformer->transform($data);

        $this->assertCount(2, $transformed);
        $this->assertCount(2, $transformed[0]->links);
        $this->assertCount(0, $transformed[1]->links);

        $this->assertSame($data['Objects'][0]['Hash'], $transformed[0]->hash);
        $this->assertSame($data['Objects'][0]['Links'][0]['Hash'], $transformed[0]->links[0]->hash);
        $this->assertSame($data['Objects'][0]['Links'][0]['ModTime'], $transformed[0]->links[0]->modTime);
        $this->assertSame($data['Objects'][0]['Links'][0]['Name'], $transformed[0]->links[0]->name);
        $this->assertSame($data['Objects'][0]['Links'][0]['Size'], $transformed[0]->links[0]->size);
        $this->assertSame($data['Objects'][0]['Links'][0]['Target'], $transformed[0]->links[0]->target);
        $this->assertSame($data['Objects'][0]['Links'][0]['Type'], $transformed[0]->links[0]->type);

        $this->assertSame($data['Objects'][0]['Links'][1]['Hash'], $transformed[0]->links[1]->hash);
        $this->assertSame($data['Objects'][0]['Links'][1]['ModTime'], $transformed[0]->links[1]->modTime);
        $this->assertSame($data['Objects'][0]['Links'][1]['Name'], $transformed[0]->links[1]->name);
        $this->assertSame($data['Objects'][0]['Links'][1]['Size'], $transformed[0]->links[1]->size);
        $this->assertSame($data['Objects'][0]['Links'][1]['Target'], $transformed[0]->links[1]->target);
        $this->assertSame($data['Objects'][0]['Links'][1]['Type'], $transformed[0]->links[1]->type);

        $this->assertSame($data['Objects'][1]['Hash'], $transformed[1]->hash);

        $this->assertEmpty($transformed[1]->links);
    }
}
