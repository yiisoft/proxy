<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

class Graph implements GraphInterface
{
    public function nodesCount(int $previousNodesCount): int
    {
        return $previousNodesCount + 1;
    }

    public function getGraphInstance(): Graph
    {
        return $this;
    }

    public function makeNewGraph(): Graph
    {
        return new self;
    }
}
