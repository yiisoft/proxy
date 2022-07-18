<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

final class Race
{
    public function time(): int
    {
        return 10;
    }
}
