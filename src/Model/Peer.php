<?php

declare(strict_types=1);

namespace IPFS\Model;

readonly class Peer
{
    /**
     * @param PeerStream[] $streams
     */
    public function __construct(
        public string $address,
        public string $identifier,
        public ?int $direction = null,
        public ?PeerIdentity $identity = null,
        public ?float $latency = null,
        public ?string $muxer = null,
        public array $streams = [],
    ) {
    }
}
