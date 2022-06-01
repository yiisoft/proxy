<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Config;

final class MethodConfig
{
    public function __construct(
        /**
         * @var string[]
         */
        public array $modifiers,
        public string $name,
        /**
         * @var ParameterConfig[]
         */
        public array $parameters,
        public bool $hasReturnType,
        public ?TypeConfig $returnType,
    ) {
    }
}
