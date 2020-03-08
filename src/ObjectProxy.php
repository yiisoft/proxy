<?php

namespace Yiisoft\Proxy;

abstract class ObjectProxy
{
    use ProxyTrait;

    private object $instance;

    public function __construct(object $instance)
    {
        $this->instance = $instance;
    }

    protected function call(string $methodName, array $arguments)
    {
        $this->resetCurrentError();
        try {
            $result = null;
            $timeStart = microtime(true);
            $result = $this->callInternal($methodName, $arguments);
        } catch (\Exception $e) {
            $this->repeatError($e);
        } finally {
            $result = $this->executeMethodProxy($methodName, $arguments, $result, $timeStart);
        }

        return $this->processResult($result);
    }

    abstract protected function executeMethodProxy(string $methodName, array $arguments, $result, float $timeStart);

    protected function getNewStaticInstance(object $instance): self
    {
        return new static($instance);
    }

    protected function getInstance(): object
    {
        return $this->instance;
    }

    private function callInternal(string $methodName, array $arguments)
    {
        return $this->instance->$methodName(...$arguments);
    }

    private function processResult($result)
    {
        if (is_object($result) && get_class($result) === get_class($this->instance)) {
            $result = $this->getNewStaticInstance($result);
        }

        return $result;
    }
}
