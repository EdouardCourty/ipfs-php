<?php

declare(strict_types=1);

namespace IPFS\Tests\Helper;

use IPFS\Helper\FilesystemHelper;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \IPFS\Helper\FilesystemHelper
 */
class FilesystemHelperTest extends TestCase
{
    /**
     * @covers ::listFiles
     */
    public function testItListFiles(): void
    {
        $currentDirectory = __DIR__;
        $result = FilesystemHelper::listFiles($currentDirectory . '/test_folder');

        $this->assertCount(2, $result);
        $this->assertContains($currentDirectory . '/test_folder/test_file_1.txt', $result);
        $this->assertContains($currentDirectory . '/test_folder/nested/test_file_2.txt', $result);
    }
}
