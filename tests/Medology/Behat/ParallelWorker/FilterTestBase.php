<?php

namespace Tests\Medology\Behat\ParallelWorker;

use Behat\Gherkin\Keywords\ArrayKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterTest.
 *
 * Base class for filter testing which sets up a Gherking Feature with several scenarios and a parser.
 */
abstract class FilterTestBase extends TestCase
{
    protected function getParser(): Parser
    {
        return new Parser(
            new Lexer(
                new ArrayKeywords([
                    'en' => [
                        'feature'          => 'Feature',
                        'background'       => 'Background',
                        'scenario'         => 'Scenario',
                        'scenario_outline' => 'Scenario Outline|Scenario Template',
                        'examples'         => 'Examples|Scenarios',
                        'given'            => 'Given',
                        'when'             => 'When',
                        'then'             => 'Then',
                        'and'              => 'And',
                        'but'              => 'But',
                    ],
                ])
            )
        );
    }

    protected function getGherkinFeature(): string
    {
        return <<<'GHERKIN'
Feature: Long feature with outline
  In order to accomplish objective
  As a someone
  I have to be able to do something

  Scenario: Scenario#1
    Given initial step
    When action occurs
    Then outcomes should be visible

  Scenario: Scenario#2
    Given initial step
    And another initial step
    When action occurs
    Then outcomes should be visible

  Scenario Outline: Scenario#3
    When <action> occurs
    Then <outcome> should be visible

    Examples:
      | action | outcome |
      | act#1  | out#1   |
      | act#2  | out#2   |
      | act#3  | out#3   |

  Scenario: Scenario#3 HD Remix
    When HDAction occurs
    Then HDOutcome should be visible
GHERKIN;
    }

    /**
     * @throws AssertionFailedError if the parser returns null (it should never do this)
     */
    protected function getParsedFeature(): FeatureNode
    {
        if (!$feature = $this->getParser()->parse($this->getGherkinFeature())) {
            throw new AssertionFailedError('Parser returned null when parsing out Gherkin fixture.');
        }

        return $feature;
    }
}
