<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use Yiisoft\Proxy\Config\ClassConfig;
use Yiisoft\Proxy\Config\MethodConfig;
use Yiisoft\Proxy\Config\ParameterConfig;
use Yiisoft\Proxy\Config\TypeConfig;

/**
 * Renders class contents based on a given config ({@see ClassConfig}).
 */
final class ClassRenderer
{
    /**
     * @var string A template for rendering class signature.
     *
     * @see renderClassSignature()
     */
    private string $classSignatureTemplate = '{{modifiers}} {{classType}} {{name}}{{extends}}{{parent}}{{implements}}';

    /**
     * @var string A template for rendering proxy method signature.
     *
     * @see renderMethodSignature()
     */
    private string $proxyMethodSignatureTemplate = '{{modifiers}} function {{name}}({{params}}){{returnType}}';

    /**
     * @var string A template for rendering proxy method body.
     *
     * @see renderMethodBody()
     */
    private string $proxyMethodBodyTemplate = '{{return}}$this->call({{methodName}}, [{{params}}]);';

    /**
     * Renders class contents to a string.
     *
     * @param ClassConfig $classConfig Class config.
     *
     * @return string Class contents as a string, opening PHP tag is not included.
     */
    public function render(ClassConfig $classConfig): string
    {
        return trim($this->renderClassSignature($classConfig))
            . "\n"
            . '{'
            . $this->renderClassBody($classConfig)
            . '}';
    }

    /**
     * Renders class / interface signature using {@see $classSignatureTemplate}.
     *
     * @param ClassConfig $classConfig Class config.
     *
     * @return string Class signature as a string.
     */
    private function renderClassSignature(ClassConfig $classConfig): string
    {
        $classType = $classConfig->isInterface
            ? 'interface'
            : 'class';
        $extends = $classConfig->parent
            ? ' extends '
            : '';

        return strtr($this->classSignatureTemplate, [
            '{{modifiers}}' => $this->renderModifiers($classConfig->modifiers),
            '{{classType}}' => $classType,
            '{{name}}' => $classConfig->shortName,
            '{{extends}}' => $extends,
            '{{parent}}' => $classConfig->parent,
            '{{implements}}' => $this->renderImplements($classConfig->interfaces),
        ]);
    }

    /**
     * Renders implements section. Used for interfaces Only
     *
     * @param string[] $interfaces A list of interfaces' names with namespaces.
     *
     * @return string Implements section as a string.
     *
     * @see ClassConfig::$interfaces
     */
    private function renderImplements(array $interfaces): string
    {
        if ($interfaces === []) {
            return '';
        }

        return ' implements ' . implode(', ', $interfaces);
    }

    /**
     * Renders modifiers section.
     *
     * @param string[] $modifiers A list of modifiers
     *
     * @return string Modifiers section as a string.
     *
     * @see ClassConfig::$modifiers
     */
    private function renderModifiers(array $modifiers): string
    {
        return implode(' ', $modifiers);
    }

    /**
     * Renders class body.
     *
     * @param ClassConfig $classConfig Class config.
     *
     * @return string Class body as a string.
     */
    private function renderClassBody(ClassConfig $classConfig): string
    {
        return $this->renderMethods($classConfig->methods);
    }

    /**
     * Renders all methods.
     *
     * @param MethodConfig[] $methods A list of method configs.
     *
     * @return string Methods' sequence as a string.
     *
     * @see ClassConfig::$methods
     */
    private function renderMethods(array $methods): string
    {
        $methodsCode = '';
        foreach ($methods as $method) {
            $methodsCode .= "\n" . $this->renderMethod($method);
        }

        return $methodsCode;
    }

    /**
     * Renders a single  method.
     *
     * @param MethodConfig $method Method config.
     *
     * @return string Method as a string.
     */
    private function renderMethod(MethodConfig $method): string
    {
        return $this->renderMethodSignature($method)
            . "\n" . $this->renderIndent()
            . '{'
            . $this->renderMethodBody($method)
            . $this->renderIndent()
            . '}'
            . "\n";
    }

    /**
     * Renders method signature using {@see $proxyMethodSignatureTemplate}.
     *
     * @param MethodConfig $method Method config.
     *
     * @return string Method signature as a string.
     */
    private function renderMethodSignature(MethodConfig $method): string
    {
        return strtr($this->proxyMethodSignatureTemplate, [
            '{{modifiers}}' => $this->renderIndent() . $this->renderModifiers($method->modifiers),
            '{{name}}' => $method->name,
            '{{params}}' => $this->renderMethodParameters($method->parameters),
            '{{returnType}}' => $this->renderReturnType($method),
        ]);
    }

    /**
     * Renders all parameters for a method.
     *
     * @param ParameterConfig[] $parameters A list of parameter configs.
     * @return string Method parameters as a string.
     */
    private function renderMethodParameters(array $parameters): string
    {
        $params = '';
        foreach ($parameters as $parameter) {
            $params .= $this->renderMethodParameter($parameter) . ', ';
        }

        return rtrim($params, ', ');
    }

    /**
     * Renders a single parameter for a method.
     *
     * @param ParameterConfig $parameter Parameter config.
     * @return string Method parameter as a string.
     */
    private function renderMethodParameter(ParameterConfig $parameter): string
    {
        $type = $parameter->hasType()
            ? $this->renderType($parameter->type)
            : '';
        $output = $type
            . ' $'
            . $parameter->name
            . $this->renderParameterDefaultValue($parameter);

        return ltrim($output);
    }

    /**
     * Renders default value for a parameter. Equal sign (surrounded with spaces) is included.
     *
     * @param ParameterConfig $parameter Parameter config.
     *
     * @return string Parameter's default value as a string.
     */
    private function renderParameterDefaultValue(ParameterConfig $parameter): string
    {
        if (!$parameter->isDefaultValueAvailable) {
            return '';
        }

        $value = $parameter->isDefaultValueConstant
            ? $parameter->defaultValueConstantName
            : var_export($parameter->defaultValue, true);

        return ' = ' . $value;
    }

    /**
     * Renders a method's body using {@see $proxyMethodBodyTemplate}.
     *
     * @param MethodConfig $method Method config.
     *
     * @return string Method body as a string.
     */
    private function renderMethodBody(MethodConfig $method): string
    {
        $output = strtr($this->proxyMethodBodyTemplate, [
            '{{return}}' => $this->renderIndent(2) . $this->renderReturn($method),
            '{{methodName}}' => "'" . $method->name . "'",
            '{{params}}' => $this->renderMethodCallParameters($method->parameters),
        ]);

        return "\n" . $output . "\n";
    }

    /**
     * Renders return statement for a method.
     *
     * @param MethodConfig $method Method config.
     * @return string Return statement as a string.
     */
    private function renderReturn(MethodConfig $method): string
    {
        if ($method->returnType?->name === 'void') {
            return '';
        }

        return 'return ';
    }

    /**
     * Renders return type for a method.
     *
     * @param MethodConfig $method Method config.
     * @return string Return type as a string.
     */
    private function renderReturnType(MethodConfig $method): string
    {
        if (!$method->hasReturnType()) {
            return '';
        }

        return ': ' . $this->renderType($method->returnType);
    }

    /**
     * Renders a type. Nullability is handled too.
     *
     * @param TypeConfig $type Type config.
     * @return string Type as a string.
     */
    private function renderType(TypeConfig $type): string
    {
        if ($type->name === 'mixed' || !$type->allowsNull) {
            return $type->name;
        }

        return '?' . $type->name;
    }

    /**
     * Renders parameters passed to a proxy's method call.
     *
     * @param ParameterConfig[] $parameters A map where key is a {@see ParameterConfig::$name} and value is
     * {@see ParameterConfig} instance.
     * @psalm-param array<string, ParameterConfig> $parameters
     *
     * @return string Parameters as a string.
     */
    private function renderMethodCallParameters(array $parameters): string
    {
        $keys = array_keys($parameters);
        if ($keys === []) {
            return '';
        }

        return '$' . implode(', $', $keys);
    }

    /**
     * Renders indent. 4 spaces are used, with no tabs.
     *
     * @param int $count How many times indent should be repeated.
     * @return string Indent as a string.
     */
    private function renderIndent(int $count = 1): string
    {
        return str_repeat('    ', $count);
    }
}
