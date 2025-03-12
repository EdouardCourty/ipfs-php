<?php

declare(strict_types=1);

namespace Ipfs\Tests\Transformer;

use Ipfs\Transformer\VersionTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ipfs\Transformer\VersionTransformer
 */
class VersionTransformerTest extends TestCase
{
    private VersionTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new VersionTransformer();
    }

    /**
     * @covers ::transform
     * @covers ::assertParameters
     */
    public function testItTransforms(): void
    {
        $data = [
            'Version' => '0.9.0',
            'Commit' => 'a1b2c3d4',
            'Repo' => 'ipfs/go-ipfs',
            'System' => 'linux',
            'Golang' => 'go1.16.3',
        ];

        $result = $this->transformer->transform($data);

        $this->assertSame($data['Version'], $result->version);
        $this->assertSame($data['Commit'], $result->commit);
        $this->assertSame($data['Repo'], $result->repo);
        $this->assertSame($data['System'], $result->system);
        $this->assertSame($data['Golang'], $result->golang);
    }
}
