<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii proxy</h1>
    <br>
</p>

The package is able to build generic proxy for a class. It's used in [yii-debug](https://github.com/yiisoft/yii-debug)
extension.

For license information check the [LICENSE](LICENSE.md)-file.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/proxy/v/stable.png)](https://packagist.org/packages/yiisoft/proxy)
[![Total Downloads](https://poser.pugx.org/yiisoft/proxy/downloads.png)](https://packagist.org/packages/yiisoft/proxy)
[![Build status](https://github.com/yiisoft/proxy/workflows/build/badge.svg)](https://github.com/yiisoft/proxy/actions?query=workflow%3Abuild)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/proxy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/proxy/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/proxy/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/proxy/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fproxy%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/proxy/master)
[![static analysis](https://github.com/yiisoft/proxy/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/proxy/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/proxy/coverage.svg)](https://shepherd.dev/github/yiisoft/proxy)

## Installation

The preferred way to install this extension is through [Сomposer](http://getcomposer.org/download/).

```
composer require --prefer-dist yiisoft/proxy
```

## Usage

### With interface

Having an interface and class implementing it, the proxy can be created like this:

```php
use Yiisoft\Proxy\ProxyManager;

interface CarInterface
{
    public function horsepower(): int;
}

class Car implements CarInterface
{
    public function horsepower(): int
    {
        return 1;
    }
}

$path = sys_get_temp_dir();
$manager = new ProxyManager(
    $path // This is optional. The proxy can be created "on the fly" instead. But it's recommended to use for caching.
);
/** @var Car|ObjectProxy $object */
$object = $manager->createObjectProxy(CarInterface::class, ObjectProxy::class, [new Car()]);
// Now you can call `Car` object methods through proxy the same as you would call it in original `Car` object.
$object->horsepower(); // Outputs "1".
```

### Without interface

An interface is not required though, the proxy still can be created almost the same way:

```php
use Yiisoft\Proxy\ProxyManager;

class Car implements CarInterface
{
    public function horsepower(): int
    {
        return 1;
    }
}

$path = sys_get_temp_dir();
$manager = new ProxyManager($path);
/** @var Car|ObjectProxy $object */
$object = $manager->createObjectProxy(
    Car::class, // Pass class instead of interface here. 
    ObjectProxy::class, 
    [new Car()]
);
$object->horsepower(); // Outputs "1".
```

### Custom base proxy class

Custom base proxy class can be useful for example to perform certain actions during each method call. 

```php
use Yiisoft\Proxy\ObjectProxy;
use Yiisoft\Proxy\ProxyManager;

class Car implements CarInterface
{
    public function horsepower(): int
    {
        return 1;
    }
}

class MyProxy extends ObjectProxy
{
    protected function afterCall(string $methodName, array $arguments, mixed $result, float $timeStart) : mixed {
        $result = parent::afterCall($methodName, $arguments, $result, $timeStart);
        
        $error = $this->getCurrentError(); // Use to track and handle errors. 
        $time = microtime(true) - $timeStart; // Use to measure / log execution time.
        
        return $result;
    }
}

$path = sys_get_temp_dir();
$manager = new ProxyManager($path);
/** @var Car|MyProxy $object */
$object = $manager->createObjectProxy(
    Car::class, 
    MyProxy::class, // Pass it here.
    [new Car()]
);
```

Additionally, you can customize new instance creation, etc. See 
[examples](https://github.com/yiisoft/yii-debug/tree/master/src/Proxy) in 
[yii-debug](https://github.com/yiisoft/yii-debug) extension.

### Proxy class contents

Here is an example how proxy class looks internally:

```php
class CarProxy extends MyProxy implements CarInterface
{
    public function horsepower(): int
    {
        return $this->call('horsepower', []);
    }
}
```

## Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

## Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

## Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Yiisoft Proxy is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).
