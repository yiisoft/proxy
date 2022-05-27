<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use Countable;
use PHPUnit\Framework\TestCase;
use Yiisoft\Files\FileHelper;
use Yiisoft\Proxy\ProxyManager;
use Yiisoft\Proxy\Tests\Stub\Car;
use Yiisoft\Proxy\Tests\Stub\Graph;
use Yiisoft\Proxy\Tests\Stub\GraphInterface;
use Yiisoft\Proxy\Tests\Stub\Proxy;

class ProxyManagerTest extends TestCase
{
    public function tearDown(): void
    {
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Yiisoft';
        FileHelper::removeDirectory($directory);
    }

    public function testCreateObjectProxyFromInterface(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);

        /** @var Graph|Proxy $object */
        $object = $manager->createObjectProxyFromInterface(GraphInterface::class, Proxy::class, [new Graph()]);
        $this->assertIsObject($object);

        $this->assertSame(2, $object->nodesCount(1));
    }

    public function testCreateObjectProxyWithNullCachePath(): void
    {
        $manager = new ProxyManager();

        /** @var Car|Proxy $object */
        $object = $manager->createObjectProxyFromInterface(Countable::class, Proxy::class, [new Car()]);
        $this->assertIsObject($object);

        $this->assertSame(1, $object->count());
    }

    public function testMethodReturningInstanceOfSameType(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);

        /** @var Graph|Proxy $object */
        $object = $manager->createObjectProxyFromInterface(GraphInterface::class, Proxy::class, [new Graph()]);

        $this->assertEquals($object, $object->getGraphInstance());
        $this->assertNotSame($object, $object->getGraphInstance());

        $this->assertEquals($object, $object->makeNewGraph());
        $this->assertNotSame($object, $object->makeNewGraph());
    }
}
