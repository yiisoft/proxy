<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

trait ProxyTrait
{
    private ?object $currentError = null;

    protected function getCurrentResultStatus(): string
    {
        return $this->currentError === null ? 'success' : 'failed';
    }

    protected function repeatError(\Throwable $error): void
    {
        $this->currentError = $error;
        $errorClass = get_class($error);
        throw new $errorClass($error->getMessage());
    }

    protected function resetCurrentError(): void
    {
        $this->currentError = null;
    }

    protected function getCurrentError(): ?object
    {
        return $this->currentError;
    }
}
