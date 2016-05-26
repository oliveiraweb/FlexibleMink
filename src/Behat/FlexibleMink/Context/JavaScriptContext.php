<?php

namespace Behat\FlexibleMink\Context;

use Behat\FlexibleMink\PseudoInterface\FlexibleContextInterface;
use Behat\FlexibleMink\PseudoInterface\JavaScriptContextInterface;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ExpectationException;

/**
 * {@inheritdoc}
 */
trait JavaScriptContext
{
    // Depends.
    use FlexibleContextInterface;

    // Implements.
    use JavaScriptContextInterface;

    /**
     * {@inheritdoc}
     *
     * @Then the javascript variable :variable should not be null
     */
    public function assertJavascriptVariableHasAValue($variable)
    {
        // Get the value of our variable from javascript
        $result = $this->getSession()->evaluateScript('return ' . $variable . ';');

        // If it's null - we failed
        if ($result === null) {
            throw new ExpectationException(
                'The custom variable "' . $variable . '" is null or does not exist.',
                $this->getSession()
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @Then /^the javascript variable "(?P<variable>.+)" should(?P<not>| not) be type "(?P<type>[\w]+)"$/
     */
    public function assertJavascriptVariableType($variable, $not, $type)
    {
        // Get the type of our variable from javascript.
        $result = $this->getSession()->evaluateScript('return typeof(' . $variable . ');');

        // If it doesn't match - we failed.
        if ($result != $type xor $not) {
            throw new ExpectationException(
                "The variable \"$variable\" should$not be type $type, but is $result",
                $this->getSession()
            );
        }
    }

    /**
     * Selectively compares two JSON objects.
     *
     * @Given /^the javascript variable "([^"]*)" should have the following contents:$/
     */
    public function assertJsonContentsOneByOne($variableName, TableNode $values, $count = null)
    {
        $returnedJsonData = $this->getSession()->evaluateScript('return JSON.stringify(' . $variableName . ');');
        $response = json_decode($returnedJsonData, true);

        foreach ($values->getHash() as $row) {
            if (!isset($response[$row['key']])) {
                throw new ExpectationException(
                    sprintf('Expected key "%s" was not in the JS variable "%s"\nActual: %s', $row['key'], $variableName, $returnedJsonData),
                    $this->getSession()
                );
            }
            $expected = $this->getRawOrJson($row['value']);
            $actual = $this->getRawOrJson($response[$row['key']]);

            if ($actual != $expected) {
                throw new ExpectationException(
                    sprintf('Expected "%s" in %s position but got "%s"', $expected, $row['key'], $actual),
                    $this->getSession()
                );
            }
        }
    }

    protected function getRawOrJson($value)
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
