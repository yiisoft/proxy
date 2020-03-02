<?php


namespace Yiisoft\Proxy\Tests;


use PHPUnit\Framework\TestCase;
use Yiisoft\Proxy\ProxyContainer;

final class ProxyContainerTest extends TestCase
{
    public function testProxied(): void
    {
        $container = new DummyContainer();
        $proxied = $container->get(Proxied::class);

        $handler = new ProxyHandler();

        $proxyContainer = new ProxyContainer($container, $handler);
        $proxy = $proxyContainer->get(Proxied::class);
        $proxy->setNumber1(1);

        $this->assertEquals(['call', 'setNumber1', [1], $proxied], end($handler->calls));
    }
}
