<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Config;

final class TypeConfig
{
    public function __construct(
        public string $name,
        public bool $allowsNull,
    ) {
    }
}
