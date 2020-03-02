<?php


namespace Yiisoft\Proxy;

use Psr\Container\ContainerInterface;

final class ProxyContainer implements ContainerInterface
{
    private ContainerInterface $proxied;
    private ProxyHandlerInterface $handler;

    public function __construct(ContainerInterface $proxied, ProxyHandlerInterface $handler)
    {
        $this->proxied = $proxied;
        $this->handler = $handler;
    }

    public function get($id)
    {
        $object = $this->proxied->get($id);
        return new Proxy($object, $this->handler);
    }

    public function has($id)
    {
        return $this->proxied->has($id);
    }
}
