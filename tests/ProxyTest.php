<?php


namespace Yiisoft\Proxy\Tests;


use PHPUnit\Framework\TestCase;
use Yiisoft\Proxy\Proxy;

final class ProxyTest extends TestCase
{
    public function testGet(): void
    {
        $handler = new ProxyHandler();
        $proxied = new Proxied();
        $proxy = new Proxy($proxied, $handler);

        $proxy->number1;

        $this->assertEquals(['get', 'number1', $proxied], end($handler->calls));
    }

    public function testSet(): void
    {
        $handler = new ProxyHandler();
        $proxied = new Proxied();
        $proxy = new Proxy($proxied, $handler);

        $proxy->number1 = 1;

        $this->assertEquals(['set', 'number1', 1, $proxied], end($handler->calls));
    }

    public function testCall(): void
    {
        $handler = new ProxyHandler();
        $proxied = new Proxied();
        $proxy = new Proxy($proxied, $handler);

        $proxy->setNumber1(1);

        $this->assertEquals(['call', 'setNumber1', [1], $proxied], end($handler->calls));
    }
}
