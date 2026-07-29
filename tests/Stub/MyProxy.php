<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use Yiisoft\Proxy\ObjectProxy;

use function in_array;

class MyProxy extends ObjectProxy
{
    private string $log = '';

    public function getLog(): string
    {
        return $this->log;
    }

    protected function afterCall(string $methodName, array $arguments, mixed $result, float $timeStart): mixed
    {
        $result = parent::afterCall($methodName, $arguments, $result, $timeStart);

        if (!in_array($methodName, ['getGraphInstance', 'makeNewGraph'])) {
            $this->log = 'Log';
        }

        return $result;
    }
}
