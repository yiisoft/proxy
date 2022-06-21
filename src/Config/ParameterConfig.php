<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Config;

final class ParameterConfig
{
    public function __construct(
        public ?TypeConfig $type,
        public string $name,
        public bool $allowsNull,
        public bool $isDefaultValueAvailable,
        public ?bool $isDefaultValueConstant,
        public ?string $defaultValueConstantName,
        public mixed $defaultValue,
    ) {
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }
}
