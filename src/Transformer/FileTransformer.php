<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\File;

class FileTransformer extends AbstractTransformer
{
    public function transform(array $input): File
    {
        $this->assertParameters($input, ['Name', 'Hash', 'Size']);

        return new File(
            name: (string) $input['Name'],
            hash: (string) $input['Hash'],
            size: (int) $input['Size'],
        );
    }
}
