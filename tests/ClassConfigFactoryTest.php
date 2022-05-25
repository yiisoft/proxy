<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Proxy\ClassConfigFactory;
use Yiisoft\Proxy\Config\ClassConfig;
use Yiisoft\Proxy\Config\MethodConfig;
use Yiisoft\Proxy\Config\ParameterConfig;
use Yiisoft\Proxy\Config\TypeConfig;
use Yiisoft\Proxy\Tests\Stub\Node;
use Yiisoft\Proxy\Tests\Stub\NodeInterface;

class ClassConfigFactoryTest extends TestCase
{
    public function testGetInterfaceConfigOfNonExistingInterface(): void
    {
        $this->expectExceptionMessage('NonExistingNodeInterface must exist');

        $factory = new ClassConfigFactory();
        $factory->getIntergaceConfig('Yiisoft\Proxy\Tests\Stub\NonExistingNodeInterface');
    }

    public function testGetInterfaceConfigOfNonInterface(): void
    {
        $this->expectExceptionMessage('Node is not an interface');

        $factory = new ClassConfigFactory();
        $factory->getIntergaceConfig(Node::class);
    }

    public function testGetInterfaceConfig(): void
    {
        $factory = new ClassConfigFactory();
        $config = $factory->getIntergaceConfig(NodeInterface::class);
        $expectedConfig = new ClassConfig(
            isInterface: true,
            namespace: 'Yiisoft\Proxy\Tests\Stub',
            modifiers: [],
            name: 'Yiisoft\Proxy\Tests\Stub\NodeInterface',
            shortName: 'NodeInterface',
            parent: '',
            interfaces: [
                'Countable',
                'Yiisoft\Proxy\Tests\Stub\NodeParentInterface',
                'Yiisoft\Proxy\Tests\Stub\NodeGrandParentInterface',
            ],
            methods: [
                'nodeInterfaceMethod1' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                        'static',
                    ],
                    name: 'nodeInterfaceMethod1',
                    parameters: [
                        'param1' => new ParameterConfig(
                            hasType: false,
                            type: null,
                            name: 'param1',
                            allowsNull: true,
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param2' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'int',
                                allowsNull: false
                            ),
                            name: 'param2',
                            allowsNull: false,
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param3' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'ArrayIterator',
                                allowsNull: false
                            ),
                            name: 'param3',
                            allowsNull: false,
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param4' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'mixed',
                                allowsNull: true
                            ),
                            name: 'param4',
                            allowsNull: true,
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param5' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'bool',
                                allowsNull: true
                            ),
                            name: 'param5',
                            allowsNull: true,
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param6' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'float',
                                allowsNull: false
                            ),
                            name: 'param6',
                            allowsNull: false,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: 3.5
                        ),
                        'param7' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'array',
                                allowsNull: false
                            ),
                            name: 'param7',
                            allowsNull: false,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: []
                        ),
                        'param8' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'string',
                                allowsNull: false
                            ),
                            name: 'param8',
                            allowsNull: false,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: true,
                            defaultValueConstantName: 'Yiisoft\Proxy\Tests\Stub\CONST1',
                            defaultValue: 'CONST1_VALUE'
                        ),
                    ],
                    hasReturnType: true,
                    returnType: new TypeConfig(
                        name: 'int',
                        allowsNull: true
                    )
                ),
                'nodeInterfaceMethod2' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'nodeInterfaceMethod2',
                    parameters: [],
                    hasReturnType: false,
                    returnType: null
                ),
                'nodeInterfaceMethod3' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'nodeInterfaceMethod3',
                    parameters: [
                        'param1' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'bool',
                                allowsNull: false
                            ),
                            name: 'param1',
                            allowsNull: false,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: false
                        ),
                        'param2' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'bool',
                                allowsNull: false
                            ),
                            name: 'param2',
                            allowsNull: false,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: true
                        ),
                        'param3' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'string',
                                allowsNull: false
                            ),
                            name: 'param3',
                            allowsNull: false,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: 'string'
                        ),
                        'param4' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'string',
                                allowsNull: true
                            ),
                            name: 'param4',
                            allowsNull: true,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param5' => new ParameterConfig(
                            hasType: true,
                            type: new TypeConfig(
                                name: 'array',
                                allowsNull: false
                            ),
                            name: 'param5',
                            allowsNull: false,
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: [1, 'value']
                        ),
                    ],
                    hasReturnType: true,
                    returnType: new TypeConfig(
                        name: 'void',
                        allowsNull: false
                    )
                ),
                'count' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'count',
                    parameters: [],
                    hasReturnType: false,
                    returnType: null
                ),
                'parentMethod1' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'parentMethod1',
                    parameters: [],
                    hasReturnType: true,
                    returnType: new TypeConfig(
                        name: 'self',
                        allowsNull: false
                    )
                ),
                'parentMethod2' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'parentMethod2',
                    parameters: [],
                    hasReturnType: false,
                    returnType: null
                ),
                'grandParentMethod1' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'grandParentMethod1',
                    parameters: [],
                    hasReturnType: true,
                    returnType: new TypeConfig(
                        name: 'ArrayObject',
                        allowsNull: false
                    )
                ),
                'grandParentMethod2' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'grandParentMethod2',
                    parameters: [],
                    hasReturnType: true,
                    returnType: new TypeConfig(
                        name: 'ArrayObject',
                        allowsNull: false
                    )
                ),
                'grandParentMethod3' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'grandParentMethod3',
                    parameters: [],
                    hasReturnType: true,
                    returnType: new TypeConfig(
                        name: 'Yiisoft\Proxy\Tests\Stub\Node',
                        allowsNull: false
                    )
                ),
                'grandParentMethod4' => new MethodConfig(
                    modifiers: [
                        'abstract',
                        'public',
                    ],
                    name: 'grandParentMethod4',
                    parameters: [],
                    hasReturnType: true,
                    returnType: new TypeConfig(
                        name: 'Yiisoft\Proxy\Tests\Stub\Node',
                        allowsNull: false
                    )
                ),
            ]
        );

        $this->assertEquals($expectedConfig, $config);
    }
}
