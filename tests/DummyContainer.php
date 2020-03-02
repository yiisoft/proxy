<?php


namespace Yiisoft\Proxy\Tests;


use Psr\Container\ContainerInterface;

final class DummyContainer implements ContainerInterface
{
    private array $instances = [];

    public function get($id)
    {
        if ($id === Proxied::class) {
            return $this->instances[$id] ?? ($this->instances[$id] = new Proxied());
        }

        throw new NotFoundException("$id not found in container.");
    }

    public function has($id): bool
    {
        return $id === Proxied::class;
    }
}
