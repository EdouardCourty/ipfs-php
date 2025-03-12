<?php

declare(strict_types=1);

namespace Ipfs\Transformer;

use Ipfs\Model\ListFileEntry;

class FileListTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly FileLinkTransformer $fileLinkTransformer,
    ) {
    }

    /**
     * @return ListFileEntry[]
     */
    public function transform(array $input): array
    {
        $data = $input['Objects'] ?? [];

        return array_map(function (array $item) {
            $this->assertParameters($item, ['Hash', 'Links']);

            return new ListFileEntry(hash: (string) $item['Hash'], links: $this->fileLinkTransformer->transformList($item['Links']));
        }, $data);
    }
}
