<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use Throwable;

trait ProxyTrait
{
    private ?object $currentError = null;

    public function getCurrentError(): ?object
    {
        return $this->currentError;
    }

    public function hasCurrentError(): bool
    {
        return $this->currentError !== null;
    }

    protected function repeatError(Throwable $error): void
    {
        $this->currentError = $error;
        throw $error;
    }

    protected function resetCurrentError(): void
    {
        $this->currentError = null;
    }
}
