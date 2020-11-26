<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

final class ClassConfig
{
    public bool $isInterface;

    public string $namespace;

    /**
     * @var string[]
     */
    public array $modifiers;

    public string $name;

    public string $shortName;

    /**
     * @var bool|string
     */
    public $parent;

    /**
     * @var string[]
     */
    public array $parents;

    /**
     * @var string[]
     */
    public array $interfaces;

    /**
     * @var MethodConfig[]
     */
    public array $methods;
}
