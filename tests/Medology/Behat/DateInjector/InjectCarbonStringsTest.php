<?php

namespace Tests\Medology\Behat\DateInjector;

use Carbon\Carbon;

/**
 * Tests that Carbon strings are injected properly.
 *
 * @covers \Medology\Behat\DateInjector::injectCarbonStrings()
 */
class InjectCarbonStringsTest extends DateInjectorTest
{
    /**
     * Sets the Carbon date/time to a known value.
     */
    public function setUp(array $methods = []): void
    {
        parent::setUp($methods);
        Carbon::setTestNow('2021-01-01 00:00:00');
    }

    /**
     * Test date are injected correctly.
     *
     * @param string $source the string that is being modified
     * @param string $result the expected result after calling injectCarbonString
     *
     * @dataProvider injectionDataProvider
     */
    public function testDatesAreInjectedCorrectly(string $source, string $result): void
    {
        self::assertSame($result, $this->dateInjector->injectCarbonStrings($source));
    }

    /**
     * Examples of injected values and their expected result.
     */
    public function injectionDataProvider(): array
    {
        return [
            ["The first Saturday was (a date/time of 'First Saturday')", 'The first Saturday was 2021-01-02 00:00:00'],
            ["Last Sunday was (a date/time of 'Last Sunday')", 'Last Sunday was 2020-12-27 00:00:00'],
            ["Today at noon is (a date/time of 'Today at noon')", 'Today at noon is 2021-01-01 12:00:00'],
            ["The party is at (a date/time of 'Tomorrow at 3:30PM'). Be there!", 'The party is at 2021-01-02 15:30:00. Be there!'],
        ];
    }
}
