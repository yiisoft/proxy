<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Yiisoft\Files\FileHelper;
use Yiisoft\Proxy\ObjectProxy;
use Yiisoft\Proxy\ProxyManager;
use Yiisoft\Proxy\ProxyTrait;
use Yiisoft\Proxy\Tests\Stub\Car;
use Yiisoft\Proxy\Tests\Stub\CarInterface;
use Yiisoft\Proxy\Tests\Stub\Graph;
use Yiisoft\Proxy\Tests\Stub\GraphInterface;
use Yiisoft\Proxy\Tests\Stub\Proxy;

/**
 * @see ProxyManager
 * @see ObjectProxy
 * @see ProxyTrait
 */
class ProxyTest extends TestCase
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
        $object = $manager->createObjectProxy(GraphInterface::class, Proxy::class, [new Graph()]);
        $this->assertIsObject($object);
        $this->assertSame('Yiisoft_Proxy_Tests_Stub_GraphInterfaceProxy', get_class($object));

        $this->assertSame(2, $object->nodesCount(1));
        $this->assertNull($object->getCurrentError());
        $this->assertFalse($object->hasCurrentError());
    }

    public function testCreateObjectProxyWithNullCachePath(): void
    {
        $manager = new ProxyManager();

        /** @var Car|Proxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, Proxy::class, [new Car()]);
        $this->assertIsObject($object);

        $this->assertSame(1, $object->horsepower());
    }

    public function testGetInstance(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);

        $instance = new Graph();
        /** @var Graph|Proxy $object */
        $object = $manager->createObjectProxy(GraphInterface::class, Proxy::class, [$instance]);
        $this->assertSame($instance, $object->getInstance());
    }

    public function testMethodReturningInstanceOfSameType(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);

        /** @var Graph|Proxy $object */
        $object = $manager->createObjectProxy(GraphInterface::class, Proxy::class, [new Graph()]);

        $this->assertEquals($object, $object->getGraphInstance());
        $this->assertNotSame($object, $object->getGraphInstance());

        $this->assertEquals($object, $object->makeNewGraph());
        $this->assertNotSame($object, $object->makeNewGraph());
    }

    public function testMethodThrowingException(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);

        /** @var Car|Proxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, Proxy::class, [new Car()]);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not implemented yet.');
        $object->ride();
    }

    public function testCurrentErrorAfterMethodThrowingException(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);
        /** @var Car|Proxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, Proxy::class, [new Car()]);

        try {
            $object->ride();
        } catch (RuntimeException) {
        }

        $this->assertNotNull($object->getCurrentError());
        $this->assertTrue($object->hasCurrentError());
    }

    public function testResetCurrentErrorAfterMethodThrowingException(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);
        /** @var Car|Proxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, Proxy::class, [new Car()]);

        try {
            $object->ride();
        } catch (RuntimeException) {
        }

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not working currently.');
        $object->park();
    }

    public function testCreateObjectProxyFromClass(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);
        /** @var Graph|Proxy $object */
        $object = $manager->createObjectProxy(Graph::class, Proxy::class, [new Graph()]);

        $this->assertSame(2, $object->edgesCount());
    }
}
