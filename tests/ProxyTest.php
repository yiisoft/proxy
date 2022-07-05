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
use Yiisoft\Proxy\Tests\Stub\MyProxy;

/**
 * @see ProxyManager
 * @see ObjectProxy
 * @see ProxyTrait
 */
class ProxyTest extends TestCase
{
    public function tearDown(): void
    {
        FileHelper::removeDirectory('php://memory');
    }

    public function testCreateObjectProxyFromInterface(): void
    {
        $manager = new ProxyManager('php://memory');

        /** @var Graph|MyProxy $object */
        $object = $manager->createObjectProxy(GraphInterface::class, MyProxy::class, [new Graph()]);
        $this->assertIsObject($object);
        $this->assertSame('Yiisoft_Proxy_Tests_Stub_GraphInterfaceProxy', get_class($object));

        $this->assertSame(2, $object->nodesCount(1));
        $this->assertNull($object->getCurrentError());
        $this->assertFalse($object->hasCurrentError());
    }

    public function testCreateObjectProxyWithNullCachePath(): void
    {
        $manager = new ProxyManager();

        /** @var Car|MyProxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, MyProxy::class, [new Car()]);
        $this->assertIsObject($object);

        $this->assertSame(1, $object->horsepower());
    }

    public function testGetInstance(): void
    {
        $manager = new ProxyManager('php://memory');

        $instance = new Graph();
        /** @var Graph|MyProxy $object */
        $object = $manager->createObjectProxy(GraphInterface::class, MyProxy::class, [$instance]);
        $this->assertSame($instance, $object->getInstance());
    }

    public function testMethodReturningInstanceOfSameType(): void
    {
        $manager = new ProxyManager('php://memory');

        /** @var Graph|MyProxy $object */
        $object = $manager->createObjectProxy(GraphInterface::class, MyProxy::class, [new Graph()]);

        $this->assertEquals($object, $object->getGraphInstance());
        $this->assertNotSame($object, $object->getGraphInstance());

        $this->assertEquals($object, $object->makeNewGraph());
        $this->assertNotSame($object, $object->makeNewGraph());
    }

    public function testMethodThrowingException(): void
    {
        $manager = new ProxyManager('php://memory');

        /** @var Car|MyProxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, MyProxy::class, [new Car()]);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not implemented yet.');
        $object->ride();
    }

    public function testCurrentErrorAfterMethodThrowingException(): void
    {
        $manager = new ProxyManager('php://memory');
        /** @var Car|MyProxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, MyProxy::class, [new Car()]);

        try {
            $object->ride();
        } catch (RuntimeException) {
        }

        $this->assertNotNull($object->getCurrentError());
        $this->assertTrue($object->hasCurrentError());
    }

    public function testResetCurrentErrorAfterMethodThrowingException(): void
    {
        $manager = new ProxyManager('php://memory');
        /** @var Car|MyProxy $object */
        $object = $manager->createObjectProxy(CarInterface::class, MyProxy::class, [new Car()]);

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
        $manager = new ProxyManager('php://memory');
        /** @var Graph|MyProxy $object */
        $object = $manager->createObjectProxy(Graph::class, MyProxy::class, [new Graph()]);

        $this->assertSame(2, $object->edgesCount());
    }
}
