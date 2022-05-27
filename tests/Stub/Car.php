<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

class Car implements CarInterface
{
    public function horsepower(): int
    {
        return 1;
    }
}
