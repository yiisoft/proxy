<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests\Stub;

use ArrayIterator;
use Countable;
use Stringable;

const CONST1 = 'CONST1_VALUE';

interface NodeInterface extends Countable, NodeParentInterface
{
    public static function nodeInterfaceMethod1(
        $param1,
        int $param2,
        ArrayIterator $param3,
        mixed $param4,
        ?bool $param5,
        float $param6 = 3.5,
        array $param7 = [],
        string $param8 = CONST1
    ): ?int;

    public function nodeInterfaceMethod2();

    public function nodeInterfaceMethod3(
        bool $param1 = false,
        bool $param2 = true,
        string $param3 = 'string',
        ?string $param4 = null,
        array $param5 = [1, 'value'],
        Stringable|string $param6 = 'stringable'
    ): void;
}
