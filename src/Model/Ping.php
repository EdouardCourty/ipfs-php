<?php

declare(strict_types=1);

namespace Ipfs\Model;

class Ping
{
    public function __construct(
        public bool $success,
        public string $text,
        public string $time,
    ) {
    }
}
