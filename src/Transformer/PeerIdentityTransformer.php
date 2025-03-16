<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\PeerIdentity;

class PeerIdentityTransformer extends AbstractTransformer
{
    public function transform(array $input): PeerIdentity
    {
        $this->assertParameters($input, ['ID', 'PublicKey'], true);

        return new PeerIdentity(
            id: (string) $input['ID'],
            publicKey: (string) $input['PublicKey'],
            agentVersion: isset($input['AgentVersion']) ? (string) $input['AgentVersion'] : null,
            addresses: $input['Addresses'] ?? [],
            protocols: $input['Protocols'] ?? [],
        );
    }
}
