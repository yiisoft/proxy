<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Files\FileHelper;
use Yiisoft\Proxy\ClassCache;
use Yiisoft\Proxy\Tests\Stub\Node;
use Yiisoft\Proxy\Tests\Stub\MyProxy;

use function sys_get_temp_dir;

class ClassCacheTest extends TestCase
{
    public function tearDown(): void
    {
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Yiisoft';
        FileHelper::removeDirectory($directory);
    }

    public function testSetAndGet(): void
    {
        $path = sys_get_temp_dir();
        $cache = new ClassCache($path);
        $classDeclaration = <<<'EOD'
class Node
{}
EOD;

        $cache->set(Node::class, MyProxy::class, $classDeclaration);
        $expectedClassDeclaration = <<<'EOD'
<?php

class Node
{}
EOD;
        $actualClassDeclaration = $cache->get(Node::class, MyProxy::class);
        $this->assertSame($expectedClassDeclaration, $actualClassDeclaration);
    }

    public function testGetWithoutSet(): void
    {
        $path = sys_get_temp_dir();
        $cache = new ClassCache($path);

        $actualClassDeclaration = $cache->get(Node::class, MyProxy::class);
        $this->assertNull($actualClassDeclaration);
    }

    public function testSetAndGetClassPath(): void
    {
        $path = sys_get_temp_dir();
        $cache = new ClassCache($path);
        $classDeclaration = <<<'EOD'
class Node
{}
EOD;
        $cache->set(Node::class, MyProxy::class, $classDeclaration);

        $expectedClassPath = implode(
            DIRECTORY_SEPARATOR,
            [sys_get_temp_dir(), 'Yiisoft', 'Proxy', 'Tests', 'Stub', 'Node.MyProxy.php']
        );
        $actualClassPath = $cache->getClassPath(Node::class, MyProxy::class);
        $this->assertEquals($expectedClassPath, $actualClassPath);
    }
}
