<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use Stringable;

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

    public function edgesCount(): int
    {
        return 2;
    }

    public function name(): Stringable|string
    {
        return 'Graph';
    }
}
