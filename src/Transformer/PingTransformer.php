<?php

declare(strict_types=1);

namespace Ipfs\Transformer;

use Ipfs\Model\Ping;

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
