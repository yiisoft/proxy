<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use InvalidArgumentException;
use Reflection;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Yiisoft\Proxy\Config\ClassConfig;
use Yiisoft\Proxy\Config\MethodConfig;
use Yiisoft\Proxy\Config\ParameterConfig;
use Yiisoft\Proxy\Config\TypeConfig;

final class ClassConfigFactory
{
    public function getInterfaceConfig(string $interfaceName): ClassConfig
    {
        try {
            $reflection = new ReflectionClass($interfaceName);
        } catch (ReflectionException) {
            throw new InvalidArgumentException("$interfaceName must exist.");
        }

        if (!$reflection->isInterface()) {
            throw new InvalidArgumentException("$interfaceName is not an interface.");
        }

        return new ClassConfig(
            isInterface: true,
            namespace: $reflection->getNamespaceName(),
            modifiers: Reflection::getModifierNames($reflection->getModifiers()),
            name: $reflection->getName(),
            shortName: $reflection->getShortName(),
            parent: (string) $reflection->getParentClass(),
            interfaces: $reflection->getInterfaceNames(),
            methods: $this->getMethodConfigs($reflection),
        );
    }

    /**
     * @return MethodConfig[]
     */
    private function getMethodConfigs(ReflectionClass $reflection): array
    {
        $methods = [];
        foreach ($reflection->getMethods() as $method) {
            $methods[$method->getName()] = $this->getMethodConfig($method);
        }

        return $methods;
    }

    private function getMethodConfig(ReflectionMethod $method): MethodConfig
    {
        return new MethodConfig(
            modifiers: Reflection::getModifierNames($method->getModifiers()),
            name: $method->getName(),
            parameters: $this->getMethodParameterConfigs($method),
            hasReturnType: $method->hasReturnType(),
            returnType: $this->getMethodTypeConfig($method),
        );
    }

    /**
     * @return ParameterConfig[]
     */
    private function getMethodParameterConfigs(ReflectionMethod $method): array
    {
        $parameters = [];
        foreach ($method->getParameters() as $param) {
            $parameters[$param->getName()] = $this->getMethodParameterConfig($param);
        }

        return $parameters;
    }

    private function getMethodParameterConfig(ReflectionParameter $param): ParameterConfig
    {
        return new ParameterConfig(
            hasType: $param->hasType(),
            type: $this->getMethodParameterTypeConfig($param),
            name: $param->getName(),
            allowsNull: $param->allowsNull(),
            isDefaultValueAvailable: $param->isDefaultValueAvailable(),
            isDefaultValueConstant: $param->isDefaultValueAvailable()
                ? $param->isDefaultValueConstant()
                : null,
            defaultValueConstantName: $param->isOptional()
                ? $param->getDefaultValueConstantName()
                : null,
            defaultValue: $param->isOptional()
                ? $param->getDefaultValue()
                : null,
        );
    }

    private function getMethodParameterTypeConfig(ReflectionParameter $param): ?TypeConfig
    {
        $type = $param->getType();
        if (!$type) {
            return null;
        }

        return new TypeConfig(
            name: $type->getName(),
            allowsNull: $type->allowsNull(),
        );
    }

    private function getMethodTypeConfig(ReflectionMethod $method): ?TypeConfig
    {
        $returnType = $method->getReturnType();
        if (!$returnType) {
            return null;
        }

        return new TypeConfig(
            name: $returnType->getName(),
            allowsNull: $returnType->allowsNull(),
        );
    }
}
