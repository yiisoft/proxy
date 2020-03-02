<?php


namespace Yiisoft\Proxy;


interface ProxyHandlerInterface
{
    public function onGet(string $name, object $object): void;
    public function onSet(string $name, $value, object $object): void;
    public function onIsset(string $name, object $object): void;
    public function onUnset(string $name, object $object): void;
    public function onCall(string $name, array $arguments, object $object): void;
}
