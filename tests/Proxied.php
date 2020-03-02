<?php


namespace Yiisoft\Proxy\Tests;


final class Proxied
{
    public int $number1 = 42;
    private int $number2 = 13;

    public function setNumber1(int $number): void
    {
        $this->number1 = $number;
    }

    public function getNumber2(): int
    {
        return $this->number2;
    }
}
