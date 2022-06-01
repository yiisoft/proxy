<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

interface NodeParentInterface extends NodeGrandParentInterface
{
    public function parentMethod1(): self;

    public function parentMethod2();
}
