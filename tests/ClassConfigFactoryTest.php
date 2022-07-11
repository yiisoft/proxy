<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Proxy\ClassConfigFactory;
use Yiisoft\Proxy\Config\ClassConfig;
use Yiisoft\Proxy\Config\MethodConfig;
use Yiisoft\Proxy\Config\ParameterConfig;
use Yiisoft\Proxy\Config\TypeConfig;
use Yiisoft\Proxy\Tests\Stub\Graph;
use Yiisoft\Proxy\Tests\Stub\NodeInterface;

class ClassConfigFactoryTest extends TestCase
{
    public function testGetClassConfigForNonExistingInterface(): void
    {
        $this->expectExceptionMessage('NonExistingNodeInterface must exist');

        $factory = new ClassConfigFactory();
        $factory->getClassConfig('Yiisoft\Proxy\Tests\Stub\NonExistingNodeInterface');
    }

    public function testGetClassConfigForInterface(): void
    {
        $factory = new ClassConfigFactory();
        $config = $factory->getClassConfig(NodeInterface::class);
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
                        'public',
                        'static',
                    ],
                    name: 'nodeInterfaceMethod1',
                    parameters: [
                        'param1' => new ParameterConfig(
                            type: null,
                            name: 'param1',
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param2' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'int',
                                allowsNull: false
                            ),
                            name: 'param2',
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param3' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'ArrayIterator',
                                allowsNull: false
                            ),
                            name: 'param3',
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param4' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'mixed',
                                allowsNull: true
                            ),
                            name: 'param4',
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param5' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'bool',
                                allowsNull: true
                            ),
                            name: 'param5',
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param6' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'float',
                                allowsNull: false
                            ),
                            name: 'param6',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: 3.5
                        ),
                        'param7' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'array',
                                allowsNull: false
                            ),
                            name: 'param7',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: []
                        ),
                        'param8' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'string',
                                allowsNull: false
                            ),
                            name: 'param8',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: true,
                            defaultValueConstantName: 'Yiisoft\Proxy\Tests\Stub\CONST1',
                            defaultValue: 'CONST1_VALUE'
                        ),
                    ],
                    returnType: new TypeConfig(
                        name: 'int',
                        allowsNull: true
                    )
                ),
                'nodeInterfaceMethod2' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'nodeInterfaceMethod2',
                    parameters: [],
                    returnType: null
                ),
                'nodeInterfaceMethod3' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'nodeInterfaceMethod3',
                    parameters: [
                        'param1' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'bool',
                                allowsNull: false
                            ),
                            name: 'param1',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: false
                        ),
                        'param2' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'bool',
                                allowsNull: false
                            ),
                            name: 'param2',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: true
                        ),
                        'param3' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'string',
                                allowsNull: false
                            ),
                            name: 'param3',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: 'string'
                        ),
                        'param4' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'string',
                                allowsNull: true
                            ),
                            name: 'param4',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                        'param5' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'array',
                                allowsNull: false
                            ),
                            name: 'param5',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: [1, 'value']
                        ),
                        'param6' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'Stringable|string',
                                allowsNull: false
                            ),
                            name: 'param6',
                            isDefaultValueAvailable: true,
                            isDefaultValueConstant: false,
                            defaultValueConstantName: null,
                            defaultValue: 'stringable'
                        ),
                    ],
                    returnType: new TypeConfig(
                        name: 'void',
                        allowsNull: false
                    )
                ),
                'count' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'count',
                    parameters: [],
                    returnType: PHP_VERSION_ID >= 80100
                        ? new TypeConfig(
                            name: 'int',
                            allowsNull: false
                        )
                        : null
                ),
                'parentMethod1' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'parentMethod1',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'self',
                        allowsNull: false
                    )
                ),
                'parentMethod2' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'parentMethod2',
                    parameters: [],
                    returnType: null
                ),
                'grandParentMethod1' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'grandParentMethod1',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'ArrayObject',
                        allowsNull: false
                    )
                ),
                'grandParentMethod2' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'grandParentMethod2',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'ArrayObject',
                        allowsNull: false
                    )
                ),
                'grandParentMethod3' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'grandParentMethod3',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'Yiisoft\Proxy\Tests\Stub\Node',
                        allowsNull: false
                    )
                ),
                'grandParentMethod4' => new MethodConfig(
                    modifiers: [
                        'public',
                    ],
                    name: 'grandParentMethod4',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'Yiisoft\Proxy\Tests\Stub\Node',
                        allowsNull: false
                    )
                ),
            ]
        );

        $this->assertEquals($expectedConfig, $config);
    }

    public function testGetClassConfigForClass(): void
    {
        $factory = new ClassConfigFactory();
        $config = $factory->getClassConfig(Graph::class);
        $expectedConfig = new ClassConfig(
            isInterface: false,
            namespace: 'Yiisoft\Proxy\Tests\Stub',
            modifiers: [],
            name: 'Yiisoft\Proxy\Tests\Stub\Graph',
            shortName: 'Graph',
            parent: '',
            interfaces: ['Yiisoft\Proxy\Tests\Stub\GraphInterface'],
            methods: [
                'nodesCount' => new MethodConfig(
                    modifiers: ['public'],
                    name: 'nodesCount',
                    parameters: [
                        'previousNodesCount' => new ParameterConfig(
                            type: new TypeConfig(
                                name: 'int',
                                allowsNull: false
                            ),
                            name: 'previousNodesCount',
                            isDefaultValueAvailable: false,
                            isDefaultValueConstant: null,
                            defaultValueConstantName: null,
                            defaultValue: null
                        ),
                    ],
                    returnType: new TypeConfig(
                        name: 'int',
                        allowsNull: false
                    )
                ),
                'getGraphInstance' => new MethodConfig(
                    modifiers: ['public'],
                    name: 'getGraphInstance',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'self',
                        allowsNull: false
                    )
                ),
                'makeNewGraph' => new MethodConfig(
                    modifiers: ['public'],
                    name: 'makeNewGraph',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'self',
                        allowsNull: false
                    )
                ),
                'edgesCount' => new MethodConfig(
                    modifiers: ['public'],
                    name: 'edgesCount',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'int',
                        allowsNull: false
                    )
                ),
                'name' => new MethodConfig(
                    modifiers: ['public'],
                    name: 'name',
                    parameters: [],
                    returnType: new TypeConfig(
                        name: 'Stringable|string',
                        allowsNull: false
                    )
                ),
            ]
        );

        $this->assertEquals($expectedConfig, $config);
    }
}
