<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Files\FileHelper;
use Yiisoft\Proxy\ProxyManager;
use Yiisoft\Proxy\Tests\Stub\Graph;
use Yiisoft\Proxy\Tests\Stub\GraphInterface;
use Yiisoft\Proxy\Tests\Stub\Proxy;

class ProxyManagerTest extends TestCase
{
    public function tearDown(): void
    {
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Yiisoft';
        FileHelper::removeDirectory($directory);
    }

    public function testCreateObjectProxyFromInterface(): void
    {
        $path = sys_get_temp_dir();
        $manager = new ProxyManager($path);

        $actualObject = $manager->createObjectProxyFromInterface(GraphInterface::class, Proxy::class, [new Graph()]);
        $this->assertIsObject($actualObject);

        $this->assertSame(2, $actualObject->nodesCount(1));
    }
}
