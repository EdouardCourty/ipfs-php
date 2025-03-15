<?php

declare(strict_types=1);

namespace IPFS\Transformer;

abstract class AbstractTransformer
{
    protected function assertParameters(array $array = [], array $mandatoryParameters = [], bool $nonEmpty = false): void
    {
        foreach ($mandatoryParameters as $parameter) {
            if (isset($array[$parameter]) === false) {
                throw new \InvalidArgumentException(\sprintf('Parameter %s is missing', $parameter));
            }

            if ($nonEmpty === true && empty($array[$parameter]) === true) {
                throw new \InvalidArgumentException(\sprintf('Parameter %s should not be empty', $parameter));
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
