<?php

declare(strict_types=1);

namespace IPFS\Transformer;

use IPFS\Model\Peer;

/**
 * @method Peer[] transformList(array $data)
 */
class PeerTransformer extends AbstractTransformer
{
    public function __construct(
        private readonly PeerIdentityTransformer $peerIdentityTransformer,
        private readonly PeerStreamTransformer $peerStreamTransformer,
    ) {
    }

    public function transform(array $input): Peer
    {
        $this->assertParameters($input, ['Addr', 'Peer']);

        $peerIdentity = null;

        try {
            $peerIdentity = $this->peerIdentityTransformer->transform($input['Identify']);
        } catch (\Throwable) {
            // Ignore
        }

        return new Peer(
            address: (string) $input['Addr'],
            identifier: (string) $input['Peer'],
            direction: isset($input['Direction']) ? (int) $input['Direction'] : null,
            identity: $peerIdentity,
            latency: isset($input['Latency']) ? (float) $input['Latency'] : null,
            muxer: isset($input['Muxer']) ? (string) $input['Muxer'] : null,
            streams: $this->peerStreamTransformer->transformList($input['Streams'] ?? []),
        );
    }
}
