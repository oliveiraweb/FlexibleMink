<?php

namespace Tests\Behat\DefaultMocks;

class MagicMethods
{
    public function __get($name)
    {
    }

    public function __set($name, $value): void
    {
    }

    public function __isset($name): bool
    {
        return false;
    }

    public function __unset($name): void
    {
    }

    public function __call($method, $arguments)
    {
    }

    public function __toString(): string
    {
        return '';
    }
}
