<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

class Square
{
    public function __construct(private float $sideLength)
    {
    }

    public function area(): float
    {
        return $this->sideLength * 2;
    }
}
