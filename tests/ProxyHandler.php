<?php


namespace Yiisoft\Proxy\Tests;


use Yiisoft\Proxy\ProxyHandlerInterface;

final class ProxyHandler implements ProxyHandlerInterface
{
    public array $calls = [];

    public function onGet(string $name, object $object): void
    {
        $this->calls[] = ['get', $name, $object];
    }

    public function onSet(string $name, $value, object $object): void
    {
        $this->calls[] = ['set', $name, $value, $object];
    }

    public function onIsset(string $name, object $object): void
    {
        $this->calls[] = ['isset', $name, $object];
    }

    public function onUnset(string $name, object $object): void
    {
        $this->calls[] = ['unset', $name, $object];
    }

    public function onCall(string $name, array $arguments, object $object): void
    {
        $this->calls[] = ['call', $name, $arguments, $object];
    }
}
