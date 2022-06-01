<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

interface CarInterface
{
    public function horsepower(): int;

    public function ride(): void;

    public function park(): void;
}
