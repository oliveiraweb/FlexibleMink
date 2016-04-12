<?php

namespace Behat\FlexibleMink\PseudoInterface;
use Behat\Mink\Exception\ExpectationException;

/**
 * Functionality for testing JavaScript variables.
 */
trait JavaScriptContextInterface
{
    /**
     * Determines if a javascript variable is set and has a value.
     *
     * @throws ExpectationException if the given variable is undefined.
     */
    abstract public function assertJavascriptVariableHasAValue($variable);

    /**
     * Determines if a javascript variable matches a specific type.
     *
     * @param  string               $variable The variable to evaluate type for.
     * @param  string               $not      Invert the check?
     * @param  string               $type     The type to match against.
     * @throws ExpectationException if the type of the given variable does not match what's expected.
     */
    abstract public function assertJavascriptVariableType($variable, $not, $type);
}
