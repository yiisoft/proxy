<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use Countable;

class Money implements Countable
{
    public function count(): int
    {
        return 1;
    }
}
