<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use Yiisoft\Proxy\Config\ClassConfig;

final class ProxyManager
{
    private ?string $cachePath = null;

    private ClassRenderer $classRenderer;

    private ClassConfigFactory $classConfigFactory;

    private ClassCache $classCache;

    public function __construct(string $cachePath = null)
    {
        $this->cachePath = $cachePath;
        $this->classCache = new ClassCache($cachePath);
        $this->classRenderer = new ClassRenderer();
        $this->classConfigFactory = new ClassConfigFactory();
    }

    public function createObjectProxyFromInterface(
        string $interface,
        string $parentProxyClass,
        array $constructorArguments = null
    ): ?object
    {
        $className = $interface . 'Proxy';
        $shortClassName = $this->getProxyClassName($className);

        if (!($classDeclaration = $this->classCache->get($className, $parentProxyClass))) {
            $classConfig = $this->generateInterfaceProxyClassConfig(
                $this->classConfigFactory->getIntergaceConfig($interface),
                $parentProxyClass
            );
            $classDeclaration = $this->classRenderer->render($classConfig);
            $this->classCache->set($className, $parentProxyClass, $classDeclaration);
        }
        if ($this->cachePath === null) {
            eval(str_replace('<?php', '', $classDeclaration));
        } else {
            $path = $this->classCache->getClassPath($className, $parentProxyClass);
            require $path;
        }
        return new $shortClassName(...$constructorArguments);
    }

    private function generateInterfaceProxyClassConfig(
        ClassConfig $interfaceConfig,
        string $parentProxyClass
    ): ClassConfig
    {
        $interfaceConfig->isInterface = false;
        $interfaceConfig->parent = $parentProxyClass;
        $interfaceConfig->interfaces = [$interfaceConfig->name];
        $interfaceConfig->name .= 'Proxy';
        $interfaceConfig->shortName = $this->getProxyClassName($interfaceConfig->name);

        foreach ($interfaceConfig->methods as $methodIndex => $method) {
            foreach ($method->modifiers as $index => $modifier) {
                if ($modifier === 'abstract') {
                    unset($interfaceConfig->methods[$methodIndex]->modifiers[$index]);
                }
            }
        }

        return $interfaceConfig;
    }

    private function getProxyClassName(string $fullClassName)
    {
        return str_replace('\\', '_', $fullClassName);
    }
}
