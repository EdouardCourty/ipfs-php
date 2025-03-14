<?php

declare(strict_types=1);

namespace IPFS\Service;

use Selective\Base32\Base32;

class CIDEncoder
{
    private const int RAW_CODEC = 0x55;

    public static function computeCIDv1(string $data): string
    {
        $multihash = self::computeMultihash($data);
        $cidBinary = pack("C*", 0x01, self::RAW_CODEC) . $multihash;

        $cidBase32 = (new Base32())->encode($cidBinary, false);
        return 'b' . mb_strtolower($cidBase32);
    }

    /**
     * Compute the multihash using SHA-256.
     */
    private static function computeMultihash(string $data): string
    {
        $hash = hash('sha256', $data, true);

        return pack("C*", 0x12, 0x20) . $hash;
    }
}
