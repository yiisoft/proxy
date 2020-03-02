<?php


namespace Yiisoft\Proxy;


final class Proxy
{
    private object $proxied;
    private ProxyHandlerInterface $handler;

    public function __construct(object $proxied, ProxyHandlerInterface $handler)
    {
        $this->proxied = $proxied;
        $this->handler = $handler;
    }

    public function __get(string $name)
    {
        $this->handler->onGet($name, $this->proxied);
        return $this->proxied->$name;
    }

    public function __set(string $name, $value)
    {
        $this->handler->onSet($name, $value, $this->proxied);
        $this->proxied->$name = $value;
    }

    public function __isset(string $name)
    {
        $this->handler->onIsset($name);
        return isset($this->proxied->$name);
    }

    public function __unset(string $name)
    {
        $this->handler->onUnset($name, $this->proxied);
        unset($this->proxied->$name);
    }

    public function __call(string $name, array $arguments)
    {
        $this->handler->onCall($name, $arguments, $this->proxied);
        $this->proxied->$name(...$arguments);
    }
}
