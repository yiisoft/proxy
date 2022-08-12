<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

final class UnionTypes
{
    public function param(string|int|null $param): void
    {
    }

    public function result(): string|int|null
    {
    }
}
