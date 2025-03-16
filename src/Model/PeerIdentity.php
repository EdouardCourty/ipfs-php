<?php

declare(strict_types=1);

namespace IPFS\Model;

readonly class PeerIdentity
{
    public function __construct(
        public string $id,
        public string $publicKey,
        public ?string $agentVersion = null,
        public array $addresses = [],
        public array $protocols = [],
    ) {
    }
}
