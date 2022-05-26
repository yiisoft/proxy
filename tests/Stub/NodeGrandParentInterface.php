<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use ArrayObject;

interface NodeGrandParentInterface
{
    public function grandParentMethod1(): ArrayObject;

    public function grandParentMethod2(): ArrayObject;

    public function grandParentMethod3(): Node;

    public function grandParentMethod4(): Node;
}
