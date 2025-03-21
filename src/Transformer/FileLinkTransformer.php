<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\FileLink;

/**
 * @method FileLink[] transformList(array $data)
 */
class FileLinkTransformer extends AbstractTransformer
{
    public function transform(array $input): FileLink
    {
        $this->assertParameters($input, ['Hash', 'ModTime', 'Mode', 'Name', 'Size', 'Target', 'Type']);

        return new FileLink(
            hash: (string) $input['Hash'],
            modTime: (string) $input['ModTime'],
            mode: (int) $input['Mode'],
            name: (string) $input['Name'],
            size: (int) $input['Size'],
            target: (string) $input['Target'],
            type: (int) $input['Type'],
        );
    }
}
