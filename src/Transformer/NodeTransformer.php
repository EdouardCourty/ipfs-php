<?php

declare(strict_types=1);

namespace Ipfs\Transformer;

use Ipfs\Model\Node;

class NodeTransformer extends AbstractTransformer
{
    public function transform(array $input): Node
    {
        $this->assertParameters($input, ['ID', 'PublicKey', 'Addresses', 'AgentVersion', 'Protocols']);

        return new Node(
            id: (string) $input['ID'],
            publicKey: (string) $input['PublicKey'],
            addresses: (array) $input['Addresses'],
            agentVersion: (string) $input['AgentVersion'],
            protocols: (array) $input['Protocols'],
        );
    }
}
