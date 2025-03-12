<?php

declare(strict_types=1);

namespace Ipfs\Transformer;

abstract class AbstractTransformer
{
    protected function assertParameters(array $array = [], array $mandatoryParameters = []): void
    {
        foreach ($mandatoryParameters as $parameter) {
            if (isset($array[$parameter]) === false) {
                throw new \InvalidArgumentException(\sprintf('Parameter %s is missing', $parameter));
            }
        }
    }

    abstract public function transform(array $input); // @phpstan-ignore-line

    public function transformList(array $data): array
    {
        return array_map(function (array $data) {
            return $this->transform($data);
        }, $data);
    }
}
