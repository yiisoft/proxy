<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

class Graph implements GraphInterface
{
    public function nodesCount(int $previousNodesCount): int
    {
        return $previousNodesCount + 1;
    }

    public function getGraphInstance(): self
    {
        return $this;
    }

    public function makeNewGraph(): self
    {
        return new self();
    }
}
