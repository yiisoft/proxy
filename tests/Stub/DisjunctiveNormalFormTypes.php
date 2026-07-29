<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use Countable;
use Stringable;

final class DisjunctiveNormalFormTypes
{
    public function param(string|int|(Stringable&Countable)|null $param): void {}

    public function result(): CarInterface|(Line&Money)|null {}
}
