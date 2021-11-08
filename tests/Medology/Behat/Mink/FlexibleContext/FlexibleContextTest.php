<?php

namespace Tests\Medology\Behat\Mink\FlexibleContext;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;
use Medology\Behat\Mink\FlexibleContext;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * Instantiates the FlexibleContext so it can be used in Unit Tests functions.
 */
abstract class FlexibleContextTest extends TestCase
{
    /** @var Session|MockObject */
    protected $sessionMock;

    /** @var DocumentElement|MockObject */
    protected $pageMock;

    /** @var FlexibleContext|MockObject */
    protected $flexible_context;

    protected $flexible_context_mocked_methods = ['getSession'];

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->flexible_context = $this->createPartialMock(FlexibleContext::class, $this->flexible_context_mocked_methods);
        $this->sessionMock = $this->createMock(Session::class);
        $this->pageMock = $this->createMock(DocumentElement::class);
        $this->flexible_context->method('getSession')->willReturn($this->sessionMock);
        $this->sessionMock->method('getPage')->willReturn($this->pageMock);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->pageMock = null;
        $this->sessionMock = null;
        $this->flexible_context = null;
    }
}
