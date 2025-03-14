<?php

declare(strict_types=1);

namespace IPFS\Tests\Service;

use IPFS\Service\CIDEncoder;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \IPFS\Service\CIDEncoder
 */
class CIDEncoderTest extends TestCase
{
    public function testItEncodes(): void
    {
        $data = 'Hello, world!';

        $expectedCIDv1 = 'bafkreibrl5n5w5wqpdcdxcwaazheualemevr7ttxzbutiw74stdvrfhn2m';
        $computedCIDv1 = CIDEncoder::computeCIDv1($data);

        $this->assertSame($expectedCIDv1, $computedCIDv1);
    }
}
