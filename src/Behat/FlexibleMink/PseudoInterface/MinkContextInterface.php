<?php namespace Behat\FlexibleMink\PseudoInterface;

use Behat\Mink\Session;

/**
 * Pseudo interface for tracking the methods of the MinkContext.
 *
 * @see Behat\MinkExtension\Context\MinkContext.
 */
trait MinkContextInterface
{
    /**
     * Checks that page contains the specified text.
     *
     * @param string $text The text that should be found on the page.
     */
    abstract public function assertPageContainsText($text);

    /**
     * Checks the checkbox with specified id|name|label|value.
     *
     * @param string $option The identifier of the option
     */
    abstract public function checkOption($option);

    /**
     * Fills in form input with specified id|name|label|value.
     *
     * @param string $field the input id, name or label
     * @param string $value the value to fill
     */
    abstract public function fillField($field, $value);

    /**
     * Returns the Mink session.
     *
     * @param  string|null $name name of the session OR active session will be used
     * @return Session
     */
    abstract public function getSession($name = null);

    /**
     * Presses the button with specified id|name|title|alt|value.
     *
     * @param string $button button id, value or alt
     */
    abstract public function pressButton($button);

    /**
     * Opens specified page.
     *
     * @param string $page The URL to visit.
     */
    abstract public function visit($page);

    /**
     * Checks, that current page PATH is equal to specified.
     *
     * @param string $page.
     */
    abstract public function assertPageAddress($page);
}
