<?php

declare(strict_types=1);

namespace IPFS\Model;

class Folder
{
    /**
     * @param File[] $files
     */
    public function __construct(
        public readonly string $name,
        public readonly string $hash,
        public readonly string $size,
        public array $files = [],
    ) {
    }

    public function addFile(File $file): self
    {
        $this->files[] = $file;

        return $this;
    }
}
