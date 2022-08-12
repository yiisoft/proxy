<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use Stringable;
use Countable;

final class IntersectionTypes
{
    public function param(Stringable&Countable $param): void
    {
    }

    public function result(): Stringable&Countable
    {
    }
}
