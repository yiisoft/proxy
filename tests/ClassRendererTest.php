<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Proxy\ClassConfigFactory;
use Yiisoft\Proxy\ClassRenderer;
use Yiisoft\Proxy\Config\ClassConfig;
use Yiisoft\Proxy\Tests\Stub\NodeGrandParentInterface;
use Yiisoft\Proxy\Tests\Stub\NodeInterface;

class ClassRendererTest extends TestCase
{
    public function testRender(): void
    {
        $factory = new ClassConfigFactory();
        $config = $factory->getClassConfig(NodeInterface::class);

        $renderer = new ClassRenderer();
        $output = $renderer->render($config);
        $expectedOutput = <<<'EOD'
interface NodeInterface implements Countable, Yiisoft\Proxy\Tests\Stub\NodeParentInterface, Yiisoft\Proxy\Tests\Stub\NodeGrandParentInterface
{
    abstract public static function nodeInterfaceMethod1($param1, int $param2, ArrayIterator $param3, mixed $param4, ?bool $param5, float $param6 = 3.5, array $param7 = array (
), string $param8 = Yiisoft\Proxy\Tests\Stub\CONST1): ?int
    {
        return $this->call('nodeInterfaceMethod1', [$param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8]);
    }

    abstract public function nodeInterfaceMethod2()
    {
        return $this->call('nodeInterfaceMethod2', []);
    }

    abstract public function nodeInterfaceMethod3(bool $param1 = false, bool $param2 = true, string $param3 = 'string', ?string $param4 = NULL, array $param5 = array (
  0 => 1,
  1 => 'value',
), Stringable|string $param6 = 'stringable'): void
    {
        $this->call('nodeInterfaceMethod3', [$param1, $param2, $param3, $param4, $param5, $param6]);
    }

    abstract public function count(): int
    {
        return $this->call('count', []);
    }

    abstract public function parentMethod1(): self
    {
        return $this->call('parentMethod1', []);
    }

    abstract public function parentMethod2()
    {
        return $this->call('parentMethod2', []);
    }

    abstract public function grandParentMethod1(): ArrayObject
    {
        return $this->call('grandParentMethod1', []);
    }

    abstract public function grandParentMethod2(): ArrayObject
    {
        return $this->call('grandParentMethod2', []);
    }

    abstract public function grandParentMethod3(): Yiisoft\Proxy\Tests\Stub\Node
    {
        return $this->call('grandParentMethod3', []);
    }

    abstract public function grandParentMethod4(): Yiisoft\Proxy\Tests\Stub\Node
    {
        return $this->call('grandParentMethod4', []);
    }
}
EOD;

        $this->assertSame($expectedOutput, $output);
    }

    public function testRenderInterfaceWithoutImplements(): void
    {
        $factory = new ClassConfigFactory();
        $config = $factory->getClassConfig(NodeGrandParentInterface::class);

        $renderer = new ClassRenderer();
        $output = $renderer->render($config);
        $expectedOutput = <<<'EOD'
interface NodeGrandParentInterface
{
    abstract public function grandParentMethod1(): ArrayObject
    {
        return $this->call('grandParentMethod1', []);
    }

    abstract public function grandParentMethod2(): ArrayObject
    {
        return $this->call('grandParentMethod2', []);
    }

    abstract public function grandParentMethod3(): Yiisoft\Proxy\Tests\Stub\Node
    {
        return $this->call('grandParentMethod3', []);
    }

    abstract public function grandParentMethod4(): Yiisoft\Proxy\Tests\Stub\Node
    {
        return $this->call('grandParentMethod4', []);
    }
}
EOD;

        $this->assertSame($expectedOutput, $output);
    }

    public function testRenderClass(): void
    {
        $config = new ClassConfig(
            isInterface: false,
            namespace: 'Yiisoft\Proxy\Tests\Stub',
            modifiers: [],
            name: 'Yiisoft\Proxy\Tests\Stub\Node',
            shortName: 'Node',
            parent: '',
            interfaces: [],
            methods: [],
        );

        $renderer = new ClassRenderer();
        $output = $renderer->render($config);
        $expectedOutput = <<<'EOD'
class Node
{}
EOD;

        $this->assertSame($expectedOutput, $output);
    }

    public function testRenderClassWithParent(): void
    {
        $config = new ClassConfig(
            isInterface: false,
            namespace: 'Yiisoft\Proxy\Tests\Stub',
            modifiers: [],
            name: 'Yiisoft\Proxy\Tests\Stub\Node',
            shortName: 'Node',
            parent: 'Yiisoft\Proxy\Tests\Stub\NodeParent',
            interfaces: [],
            methods: [],
        );

        $renderer = new ClassRenderer();
        $output = $renderer->render($config);
        $expectedOutput = <<<'EOD'
class Node extends Yiisoft\Proxy\Tests\Stub\NodeParent
{}
EOD;

        $this->assertSame($expectedOutput, $output);
    }

    public function testRenderClassWithParentAndInterfaces(): void
    {
        $config = new ClassConfig(
            isInterface: false,
            namespace: 'Yiisoft\Proxy\Tests\Stub',
            modifiers: [],
            name: 'Yiisoft\Proxy\Tests\Stub\Node',
            shortName: 'Node',
            parent: 'Yiisoft\Proxy\Tests\Stub\NodeParent',
            interfaces: [
                'Countable',
                'Yiisoft\Proxy\Tests\Stub\NodeParentInterface',
                'Yiisoft\Proxy\Tests\Stub\NodeGrandParentInterface',
            ],
            methods: [],
        );

        $renderer = new ClassRenderer();
        $output = $renderer->render($config);
        $expectedOutput = <<<'EOD'
class Node extends Yiisoft\Proxy\Tests\Stub\NodeParent implements Countable, Yiisoft\Proxy\Tests\Stub\NodeParentInterface, Yiisoft\Proxy\Tests\Stub\NodeGrandParentInterface
{}
EOD;

        $this->assertSame($expectedOutput, $output);
    }
}
