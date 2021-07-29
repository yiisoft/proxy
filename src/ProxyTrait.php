<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use Throwable;

trait ProxyTrait
{
    private ?object $currentError = null;

    protected function getCurrentResultStatus(): string
    {
        return $this->currentError === null ? 'success' : 'failed';
    }

    /**
     * @throws Throwable
     */
    protected function repeatError(Throwable $error): void
    {
        $this->currentError = clone $error;
        throw $error;
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
