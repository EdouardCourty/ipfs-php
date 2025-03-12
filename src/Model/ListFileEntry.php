<?php

declare(strict_types=1);

namespace IPFS\Model;

readonly class ListFileEntry
{
    public function __construct(
        public string $hash,
        public array $links = [],
    ) {
    }
}
