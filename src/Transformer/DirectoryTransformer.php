<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\Directory;

class DirectoryTransformer extends AbstractTransformer
{
    public function transform(array $input): Directory
    {
        $this->assertParameters($input, ['Name', 'Hash', 'Size']);

        return new Directory(
            name: $input['Name'],
            hash: $input['Hash'],
            size: $input['Size'],
            files: [],
        );
    }
}
