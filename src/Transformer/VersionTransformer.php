<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\Version;

class VersionTransformer extends AbstractTransformer
{
    public function transform(array $input): Version
    {
        $this->assertParameters($input, ['Version', 'Commit', 'Repo', 'System', 'Golang']);

        return new Version(
            commit: (string) $input['Commit'],
            golang: (string) $input['Golang'],
            repo: (string) $input['Repo'],
            system: (string) $input['System'],
            version: (string) $input['Version'],
        );
    }
}
