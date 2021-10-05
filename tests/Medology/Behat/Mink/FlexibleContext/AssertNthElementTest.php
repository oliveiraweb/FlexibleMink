<?php

namespace Tests\Medology\Behat\Mink\FlexibleContext;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ExpectationException;

/**
 * @covers \Medology\Behat\Mink\FlexibleContext::assertNthElement()
 */
class AssertNthElementTest extends FlexibleContextTest
{
    public function testThrowsExpectationExceptionWhenNoElementFound(): void
    {
        $this->pageMock->method('findAll')->willReturn([]);
        $this->expectException(ExpectationException::class);
        $this->expectExceptionMessage('No \'image\' was found');
        $this->flexible_context->assertNthElement('image', 1);
    }

    public function testThrowsExpectationExceptionWhenNthElementNotFound(): void
    {
        $this->pageMock->method('findAll')->willReturn([$this->createMock(NodeElement::class)]);
        $this->expectException(ExpectationException::class);
        $this->expectExceptionMessage('Element image 2 was not found');
        $this->flexible_context->assertNthElement('image', 2);
    }

    public function testReturnsFoundNthElements(): void
    {
        $expectedElements = [$this->createMock(NodeElement::class), $this->createMock(NodeElement::class)];
        $this->pageMock->method('findAll')->willReturn($expectedElements);
        $elements = $this->flexible_context->assertNthElement('image', 2);
        $this->assertEquals($expectedElements[1], $elements);
    }
}
