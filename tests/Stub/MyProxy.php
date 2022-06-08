<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use Yiisoft\Proxy\ObjectProxy;

class MyProxy extends ObjectProxy
{
    protected function executeMethodProxy(string $methodName, array $arguments, $result, float $timeStart)
    {
        return $result;
    }
}
