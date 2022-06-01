<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use RuntimeException;

class Car implements CarInterface
{
    public function horsepower(): int
    {
        return 1;
    }

    public function ride(): void
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function park(): void
    {
        throw new RuntimeException('Not working currently.');
    }
}
