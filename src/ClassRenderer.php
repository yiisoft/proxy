<?php

declare(strict_types=1);

namespace Yiisoft\Proxy;

use Yiisoft\Proxy\Config\ClassConfig;
use Yiisoft\Proxy\Config\MethodConfig;
use Yiisoft\Proxy\Config\ParameterConfig;
use Yiisoft\Proxy\Config\TypeConfig;

final class ClassRenderer
{
    private string $classSignatureTemplate = '{{modifiers}} {{classType}} {{name}}{{extends}}{{parent}}{{implements}}';

    private string $proxyMethodSignatureTemplate = '{{modifiers}} function {{name}}({{params}}){{returnType}}';

    private string $proxyMethodBodyTemplate = '{{return}}$this->call({{methodName}}, [{{params}}]);';

    public function render(ClassConfig $classConfig): string
    {
        return trim($this->renderClassSignature($classConfig))
            . "\n"
            . '{'
            . $this->renderClassBody($classConfig)
            . '}';
    }

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

    private function renderImplements(array $interfaces): string
    {
        if ($interfaces === []) {
            return '';
        }

        return ' implements ' . implode(', ', $interfaces);
    }

    private function renderModifiers(array $modifiers): string
    {
        return implode(' ', $modifiers);
    }

    private function renderClassBody(ClassConfig $classConfig): string
    {
        return $this->renderMethods($classConfig->methods);
    }

    /**
     * @param MethodConfig[] $methods
     *
     * @return string
     */
    private function renderMethods(array $methods): string
    {
        $methodsCode = '';
        foreach ($methods as $method) {
            $methodsCode .= "\n" . $this->renderMethod($method);
        }

        return $methodsCode;
    }

    private function renderMethod(MethodConfig $method): string
    {
        return $this->renderMethodSignature($method)
            . "\n" . $this->margin()
            . '{'
            . $this->renderMethodBody($method)
            . $this->margin()
            . '}'
            . "\n";
    }

    private function renderMethodSignature(MethodConfig $method): string
    {
        return strtr($this->proxyMethodSignatureTemplate, [
            '{{modifiers}}' => $this->margin() . $this->renderModifiers($method->modifiers),
            '{{name}}' => $method->name,
            '{{params}}' => $this->renderMethodParameters($method->parameters),
            '{{returnType}}' => $this->renderReturnType($method),
        ]);
    }

    private function renderMethodParameters(array $parameters): string
    {
        $params = '';
        foreach ($parameters as $parameter) {
            $params .= $this->renderMethodParameter($parameter) . ', ';
        }

        return rtrim($params, ', ');
    }

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

    private function renderParameterDefaultValue(ParameterConfig $parameter): string
    {
        if (!$parameter->isDefaultValueAvailable) {
            return '';
        }

        $value = $parameter->isDefaultValueConstant
            ? $parameter->defaultValueConstantName
            : self::varExport($parameter->defaultValue);

        return ' = ' . $value;
    }

    private function renderMethodBody(MethodConfig $method): string
    {
        $output = strtr($this->proxyMethodBodyTemplate, [
            '{{return}}' => $this->margin(2) . $this->renderReturn($method),
            '{{methodName}}' => "'" . $method->name . "'",
            '{{params}}' => $this->renderMethodCallParameters($method->parameters),
        ]);

        return "\n" . $output . "\n";
    }

    private function renderReturn(MethodConfig $method): string
    {
        if ($method->returnType?->name === 'void') {
            return '';
        }

        return 'return ';
    }

    private function renderReturnType(MethodConfig $method): string
    {
        if (!$method->hasReturnType()) {
            return '';
        }

        return ': ' . $this->renderType($method->returnType);
    }

    private function renderType(TypeConfig $type): string
    {
        if ($type->name === 'mixed' || !$type->allowsNull) {
            return $type->name;
        }

        return '?' . $type->name;
    }

    private function renderMethodCallParameters(array $parameters): string
    {
        $keys = array_keys($parameters);
        if ($keys === []) {
            return '';
        }

        return '$' . implode(', $', $keys);
    }

    private static function varExport(mixed $var): string
    {
        $output = '';
        switch (gettype($var)) {
            case 'boolean':
                $output = $var
                    ? 'true'
                    : 'false';
                break;
            case 'integer':
            case 'double':
                $output = (string)$var;
                break;
            case 'string':
                $output = "'" . addslashes($var) . "'";
                break;
            case 'NULL':
                $output = 'null';
                break;
            case 'array':
                if (empty($var)) {
                    $output .= '[]';
                } else {
                    $keys = array_keys($var);
                    $output .= '[';
                    foreach ($keys as $index => $key) {
                        $output .= self::varExport($key);
                        $output .= ' => ';
                        $output .= self::varExport($var[$key]);

                        if ($index !== array_key_last($keys)) {
                            $output .= ', ';
                        }
                    }
                    $output .= ']';
                }
                break;
        }

        return $output;
    }

    private function margin(int $count = 1): string
    {
        return str_repeat('    ', $count);
    }
}
