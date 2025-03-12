<?php

declare(strict_types=1);

namespace IPFS\Model;

readonly class File
{
    public function __construct(
        public string $name,
        public string $hash,
        public int $size,
    ) {
    }
}
