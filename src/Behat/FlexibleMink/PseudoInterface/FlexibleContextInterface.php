<?php

namespace Behat\FlexibleMink\PseudoInterface;

use Behat\Mink\Session;

/**
 * Pseudo trait for tracking the methods of the FlexibleContext.
 */
trait FlexibleContextInterface
{
    /**
     * This method overrides the MinkContext::assertPageContainsText() default behavior for assertPageContainsText to
     * ensure that it waits for the text to be available with a max time limit.
     *
     * @param string $text Text to be searched in the page.
     */
    abstract public function assertPageContainsText($text);

    /**
     * Returns Mink session.
     *
     * @param  string|null $name name of the session OR active session will be used
     * @return Session
     */
    abstract public function getSession($name = null);
}
