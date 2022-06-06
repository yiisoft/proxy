<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use Yiisoft\Proxy\Config\ClassConfig;

final class ProxyManager
{
    private ?string $cachePath;

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

    public function createObjectProxy(
        string $baseStructure,
        string $parentProxyClass,
        array $constructorArguments
    ): ?object {
        $className = $baseStructure . 'Proxy';
        $shortClassName = $this->getProxyClassName($className);

        if (class_exists($shortClassName)) {
            return new $shortClassName(...$constructorArguments);
        }

        if (!($classDeclaration = $this->classCache->get($className, $parentProxyClass))) {
            $classConfig = $this->classConfigFactory->getClassConfig($baseStructure);
            $classConfig = $this->generateProxyClassConfig($classConfig, $parentProxyClass);
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

    private function generateProxyClassConfig(
        ClassConfig $classConfig,
        string $parentProxyClass
    ): ClassConfig {
        if ($classConfig->isInterface) {
            $classConfig->isInterface = false;
            $classConfig->interfaces = [$classConfig->name];
        }

        $classConfig->parent = $parentProxyClass;
        $classConfig->name .= 'Proxy';
        $classConfig->shortName = $this->getProxyClassName($classConfig->name);

        foreach ($classConfig->methods as $methodIndex => $method) {
            foreach ($method->modifiers as $index => $modifier) {
                if ($modifier === 'abstract') {
                    unset($classConfig->methods[$methodIndex]->modifiers[$index]);
                }
            }
        }

        return $classConfig;
    }

    private function getProxyClassName(string $fullClassName)
    {
        return str_replace('\\', '_', $fullClassName);
    }
}
