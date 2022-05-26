<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

interface GraphInterface
{
    public function nodesCount(int $previousNodesCount): int;
}
