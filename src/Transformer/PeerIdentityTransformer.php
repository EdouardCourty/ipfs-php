<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\PeerIdentity;

class PeerIdentityTransformer extends AbstractTransformer
{
    public function transform(array $input): PeerIdentity
    {
        $this->assertParameters($input, ['ID', 'AgentVersion', 'PublicKey'], true);

        return new PeerIdentity(
            id: (string) $input['ID'],
            agentVersion: (string) $input['AgentVersion'],
            publicKey: (string) $input['PublicKey'],
            addresses: $input['Addresses'] ?? [],
            protocols: $input['Protocols'] ?? [],
        );
    }
}
