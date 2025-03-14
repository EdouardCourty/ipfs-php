<?php

declare(strict_types=1);

namespace IPFS\Model;

readonly class Ping
{
    public function __construct(
        public bool $success,
        public string $text,
        public string $time,
    ) {
    }
}
