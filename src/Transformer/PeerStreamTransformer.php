<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\PeerStream;

/**
 * @method PeerStream[] transformList(array $data)
 */
class PeerStreamTransformer extends AbstractTransformer
{
    public function transform(array $input): PeerStream
    {
        $this->assertParameters($input, ['Protocol']);

        return new PeerStream(
            protocol: (string) $input['Protocol'],
        );
    }
}
