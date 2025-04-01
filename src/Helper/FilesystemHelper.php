<?php

declare(strict_types=1);

namespace IPFS\Helper;

class FilesystemHelper
{
    /**
     * @return string[]
     */
    public static function listFiles(string $directory): array
    {
        if (is_dir($directory) === false) {
            throw new \InvalidArgumentException('Directory not found.');
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $files[] = $fileInfo->getRealPath();
            }
        }

        return $files;
    }
}
