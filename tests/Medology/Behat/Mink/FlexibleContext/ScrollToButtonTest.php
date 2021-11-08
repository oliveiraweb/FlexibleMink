<?php

namespace Tests\Medology\Behat\Mink\FlexibleContext;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Element\TraversableElement;
use Behat\Mink\Exception\ExpectationException;
use PHPUnit\Framework\MockObject\MockObject;

class ScrollToButtonTest extends FlexibleContextTest
{
    /** @var TraversableElement|MockObject */
    protected $context;

    /** @var NodeElement|MockObject */
    protected $element1;

    /** @var NodeElement|MockObject */
    protected $element2;

    /** @var NodeElement[]|MockObject[] */
    protected $expectedElements;

    protected $locator = 'button';

    public function setUp(): void
    {
        $this->flexible_context_mocked_methods = [];
        array_push(
            $this->flexible_context_mocked_methods,
            'getSession',
            'fixStepArgument',
            'scrollWindowToFirstVisibleElement'
        );

        parent::setUp();
    }

    public function testThrowsExceptionWhenButtonIsNotVisibleInPage(): void
    {
        $this->flexible_context->method('fixStepArgument')->willReturn($this->locator);
        $this->pageMock->method('findAll')->willReturn([]);
        $this->createAndExpectExpectationException("No visible button found for '$this->locator'");
        $this->flexible_context->scrollToButton($this->locator);
    }

    public function testThrowsExceptionWhenButtonIsNotVisibleInContext(): void
    {
        $this->flexible_context->method('fixStepArgument')->willReturn($this->locator);
        $this->mockContext();
        $this->context->method('findAll')->willReturn([]);

        $this->createAndExpectExpectationException("No visible button found for '$this->locator'");
        $this->flexible_context->scrollToButton('$this->locator', $this->context);
    }

    public function testReturnsFoundButtonInPage(): void
    {
        $this->initElements();
        $this->flexible_context->method('fixStepArgument')->willReturn($this->locator);
        $this->pageMock->method('findAll')->willReturn($this->expectedElements);
        $this->flexible_context->method('scrollWindowToFirstVisibleElement')->willReturn($this->element1);
        $elements = $this->flexible_context->scrollToButton($this->locator);
        $this->assertEquals($elements, $this->element1);
    }

    public function testReturnsFoundButtonInContext(): void
    {
        $this->initElements();
        $this->flexible_context->method('fixStepArgument')->willReturn($this->locator);
        $this->mockContext();
        $this->context->method('findAll')->willReturn($this->expectedElements);
        $this->flexible_context->method('scrollWindowToFirstVisibleElement')->willReturn($this->element1);
        $elements = $this->flexible_context->scrollToButton($this->locator, $this->context);
        $this->assertEquals($elements, $this->element1);
    }

    /** This method Initializes the elements to be used as return values. */
    protected function initElements(): void
    {
        $this->element1 = $this->createPartialMock(NodeElement::class, []);
        $this->element2 = $this->createPartialMock(NodeElement::class, []);
        $this->expectedElements = [$this->element1, $this->element2];
    }

    /** This method mocks the Context to be passed as an parameter to scrollToButton function. */
    protected function mockContext(): void
    {
        $this->context = $this->getMockForAbstractClass(
            TraversableElement::class,
            [$this->sessionMock],
            '',
            true,
            true,
            true,
            ['findAll']
        );
    }

    /**
     * This method will create and expect the ExpectationException.
     *
     * @param string $exceptionMessage The message while the exception is thrown
     */
    protected function createAndExpectExpectationException(string $exceptionMessage): void
    {
        $exception = new ExpectationException($exceptionMessage, $this->sessionMock);
        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());
    }
}
