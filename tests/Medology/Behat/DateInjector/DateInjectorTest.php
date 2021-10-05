<?php

namespace Tests\Medology\Behat\DateInjector;

use Medology\Behat\DateInjector;
use PHPUnit_Framework_MockObject_MockObject;
use Tests\TestCase;

/**
 * Instantiates the DateInjector so it can be used in Unit Tests.
 */
abstract class DateInjectorTest extends TestCase
{
    /** @var DateInjector|PHPUnit_Framework_MockObject_MockObject */
    protected $dateInjector;

    /**
     * {@inheritdoc}
     */
    public function setUp(array $methods = []): void
    {
        $this->dateInjector = $this->createPartialMock(DateInjector::class, $methods);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->dateInjector = null;
    }
}
