<?php

declare(strict_types=1);

namespace Ipfs\Model;

class File
{
    public function __construct(
        public string $name,
        public string $hash,
        public int $size,
    ) {
    }
}
