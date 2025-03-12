<?php

declare(strict_types=1);

namespace Ipfs\Model;

readonly class Version
{
    public function __construct(
        public string $commit,
        public string $golang,
        public string $repo,
        public string $system,
        public string $version,
    ) {
    }
}
