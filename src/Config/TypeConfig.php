<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Config;

/**
 * A type metadata. {@see ClassConfigFactory} is used for creation. Can be used both for method parameters' types and
 * return type. Note that it relies only on PHP type hints and ignores PHPDoc completely.
 */
final class TypeConfig
{
    public function __construct(
        /**
         * @var string The string representation of a type, for example: `int`, `bool`, etc. In case of a class it's a
         * full path including namespace, for example `Yiisoft\Proxy\Tests\Stub\Node`. For built-in classes like
         * `ArrayIterator` the leading slash is not included.
         * @link https://www.php.net/manual/en/language.types.declarations.php
         */
        public string $name,
        /**
         * @var bool Whether the null values are allowed.
         */
        public bool $allowsNull,
    ) {
    }
}
