<?php


namespace Yiisoft\Proxy\Tests;


use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{

}
