<?php

declare(strict_types=1);

namespace IPFS\Helper;

class FilesystemHelper
{
    /**
     * @return string[]
     */
    public static function listFiles(string $folder): array
    {
        if (is_dir($folder) === false) {
            throw new \InvalidArgumentException('Folder not found.');
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS),
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $files[] = $fileInfo->getRealPath();
            }
        }

        return $files;
    }
}
