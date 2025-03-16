<?php

declare(strict_types=1);

namespace IPFS\Model;

readonly class PeerStream
{
    public function __construct(
        public string $protocol,
    ) {
    }
}
