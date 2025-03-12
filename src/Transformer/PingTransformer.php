<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\Ping;

class PingTransformer extends AbstractTransformer
{
    public function transform(array $input): Ping
    {
        $this->assertParameters($input, ['Success', 'Text', 'Time']);

        return new Ping(
            success: (bool) $input['Success'],
            text: (string) $input['Text'],
            time: (string) $input['Time'],
        );
    }
}
