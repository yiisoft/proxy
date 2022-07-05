<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use InvalidArgumentException;
use Reflection;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Yiisoft\Proxy\Config\ClassConfig;
use Yiisoft\Proxy\Config\MethodConfig;
use Yiisoft\Proxy\Config\ParameterConfig;
use Yiisoft\Proxy\Config\TypeConfig;

/**
 * A factory for creating class configs ({@see ClassConfig}). Uses PHP `Reflection` to get the necessary metadata.
 *
 * @link https://www.php.net/manual/en/book.reflection.php
 */
final class ClassConfigFactory
{
    /**
     * Gets single class config based for individual class.
     *
     * @param string $className Full class or interface name (including namespace).
     *
     * @throws InvalidArgumentException In case class or interface does not exist.
     *
     * @return ClassConfig Class config with all related configs (methods, parameters, types) linked.
     */
    public function getClassConfig(string $className): ClassConfig
    {
        try {
            $reflection = new ReflectionClass($className);
        } catch (ReflectionException) {
            throw new InvalidArgumentException("$className must exist.");
        }

        return new ClassConfig(
            isInterface: $reflection->isInterface(),
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
     * Gets the complete set of method configs for a given class reflection.
     *
     * @param ReflectionClass $class Reflection of a class.
     *
     * @return MethodConfig[] List of method configs. The order is maintained.
     */
    private function getMethodConfigs(ReflectionClass $class): array
    {
        $methods = [];
        foreach ($class->getMethods() as $method) {
            $methods[$method->getName()] = $this->getMethodConfig($method);
        }

        return $methods;
    }

    /**
     * Gets single method config for individual method reflection.
     *
     * @param ReflectionMethod $method Reflection of a method.
     *
     * @return MethodConfig Single method config.
     */
    private function getMethodConfig(ReflectionMethod $method): MethodConfig
    {
        return new MethodConfig(
            modifiers: Reflection::getModifierNames($method->getModifiers()),
            name: $method->getName(),
            parameters: $this->getMethodParameterConfigs($method),
            returnType: $this->getMethodReturnTypeConfig($method),
        );
    }

    /**
     * Gets the complete set of parameter configs for a given method reflection.
     *
     * @param ReflectionMethod $method Reflection of a method.
     *
     * @return ParameterConfig[] List of parameter configs. The order is maintained.
     */
    private function getMethodParameterConfigs(ReflectionMethod $method): array
    {
        $parameters = [];
        foreach ($method->getParameters() as $param) {
            $parameters[$param->getName()] = $this->getMethodParameterConfig($param);
        }

        return $parameters;
    }

    /**
     * Gets single parameter config for individual method's parameter reflection.
     *
     * @param ReflectionParameter $param Reflection of a method's parameter.
     *
     * @return ParameterConfig Single parameter config.
     */
    private function getMethodParameterConfig(ReflectionParameter $param): ParameterConfig
    {
        return new ParameterConfig(
            type: $this->getMethodParameterTypeConfig($param),
            name: $param->getName(),
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

    /**
     * Gets single type config for individual method's parameter reflection.
     *
     * @param ReflectionParameter $param Reflection pf a method's parameter.
     *
     * @return TypeConfig|null Single type config. `null` is returned when type is not specified.
     */
    private function getMethodParameterTypeConfig(ReflectionParameter $param): ?TypeConfig
    {
        $type = $param->getType();
        if (!$type) {
            return null;
        }

        if ($type instanceof ReflectionUnionType) {
            $name = $this->getUnionType($type);
        } else {
            /** @var ReflectionNamedType $type */
            $name = $type->getName();
        }

        return new TypeConfig(
            name: $name,
            allowsNull: $type->allowsNull(),
        );
    }

    /**
     * Gets single return type config for individual method reflection.
     *
     * @param ReflectionMethod $method Reflection of a method.
     *
     * @return TypeConfig|null Single type config. `null` is returned when return type is not specified.
     */
    private function getMethodReturnTypeConfig(ReflectionMethod $method): ?TypeConfig
    {
        $returnType = $method->getReturnType();
        if (!$returnType) {
            return null;
        }

        if ($returnType instanceof ReflectionUnionType) {
            $name = $this->getUnionType($returnType);
        } else {
            /** @var ReflectionNamedType $returnType */
            $name = $returnType->getName();
        }

        return new TypeConfig(
            name: $name,
            allowsNull: $returnType->allowsNull(),
        );
    }

    private function getUnionType(ReflectionUnionType $type): string
    {
        $types = array_map(
            static fn (ReflectionNamedType $namedType) => $namedType->getName(),
            $type->getTypes()
        );

        return implode('|', $types);
    }
}
