<?php

declare(strict_types=1);

namespace Ipfs\Model;

readonly class Node
{
    public function __construct(
        public string $id,
        public string $publicKey,
        public array $addresses,
        public string $agentVersion,
        public array $protocols,
    ) {
    }
}
