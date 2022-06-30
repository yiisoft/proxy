<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use Stringable;

interface GraphInterface
{
    public function nodesCount(int $previousNodesCount): int;

    public function getGraphInstance(): self;

    public function makeNewGraph(): self;

    public function name(): Stringable|string;
}
