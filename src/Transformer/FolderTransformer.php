<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\Folder;

class FolderTransformer extends AbstractTransformer
{
    public function transform(array $input): Folder
    {
        $this->assertParameters($input, ['Name', 'Hash', 'Size']);

        return new Folder(
            name: $input['Name'],
            hash: $input['Hash'],
            size: $input['Size'],
            files: [],
        );
    }
}
