<?php

declare(strict_types=1);

namespace Ipfs\Transformer;

use Ipfs\Model\Version;

class VersionTransformer extends AbstractTransformer
{
    public function transform(array $input): Version
    {
        $this->assertParameters($input, ['Version', 'Commit', 'Repo', 'System', 'Golang']);

        return new Version(
            commit: $input['Commit'],
            golang: $input['Golang'],
            repo: $input['Repo'],
            system: $input['System'],
            version: $input['Version'],
        );
    }
}
