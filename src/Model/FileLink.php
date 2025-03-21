<?php

declare(strict_types=1);

namespace IPFS\Model;

readonly class FileLink
{
    public function __construct(
        public string $hash,
        public string $modTime,
        public int $mode,
        public string $name,
        public int $size,
        public string $target,
        public int $type,
    ) {
    }
}
