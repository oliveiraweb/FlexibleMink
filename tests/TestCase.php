<?php

namespace Tests;

use \PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Call protected/private method of a class.
     *
     * @param object|string $object     Instantiated object that we will run method on
     * @param string        $methodName Method name to call
     * @param array         $parameters array of parameters to pass into method
     *
     * @throws ReflectionException if there is a problem invoking the method
     *
     * @return mixed method return
     */
    public function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs((is_string($object) ? null : $object), $parameters);
    }
}
