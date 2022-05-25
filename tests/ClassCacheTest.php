<?php

declare(strict_types=1);

namespace Yiisoft\Proxy\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Files\FileHelper;
use Yiisoft\Proxy\ClassCache;
use Yiisoft\Proxy\Tests\Stub\Node;
use function sys_get_temp_dir;

class ClassCacheTest extends TestCase
{
    public function tearDown(): void
    {
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Yiisoft';
        FileHelper::removeDirectory($directory);
    }

    public function testSetGet(): void
    {
        $path = sys_get_temp_dir();
        $cache = new ClassCache($path);
        $classDeclaration = <<<'EOD'
class Node
{}
EOD;

        $cache->set(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent', $classDeclaration);
        $expectedClassDeclaration = <<<'EOD'
<?php

class Node
{}
EOD;
        $actualClassDeclaration = $cache->get(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent');
        $this->assertSame($expectedClassDeclaration, $actualClassDeclaration);
    }

    public function testSetGetWithNullCachePath(): void
    {
        $cache = new ClassCache();
        $classDeclaration = <<<'EOD'
class Node
{}
EOD;

        $cache->set(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent', $classDeclaration);
        $actualClassDeclaration = $cache->get(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent');
        $this->assertNull($actualClassDeclaration);
    }

    public function testGetWithoutSet(): void
    {
        $path = sys_get_temp_dir();
        $cache = new ClassCache($path);

        $actualClassDeclaration = $cache->get(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent');
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
        $cache->set(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent', $classDeclaration);

        $actualClassPath = $cache->getClassPath(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent');
        $this->assertEquals('/tmp/Yiisoft/Proxy/Tests/Stub/Node.NodeParent.php', $actualClassPath);
    }

    public function testSetAndGetClassPathWithNullCachePath(): void
    {
        $cache = new ClassCache();
        $classDeclaration = <<<'EOD'
class Node
{}
EOD;
        $cache->set(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent', $classDeclaration);

        $this->expectExceptionMessage('Directory "/Yiisoft/Proxy/Tests/Stub" was not created');
        $cache->getClassPath(Node::class, 'Yiisoft\Proxy\Tests\Stub\NodeParent');
    }
}
