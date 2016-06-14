<?php

namespace Behat\FlexibleMink\Context;

use Behat\FlexibleMink\PseudoInterface\AlertContextInterface;
use Behat\FlexibleMink\PseudoInterface\FlexibleContextInterface;
use Behat\Mink\Exception\ExpectationException;

/**
 * {@inheritdoc}
 */
trait AlertContext
{
    // Implements.
    use AlertContextInterface;

    // Depends.
    use FlexibleContextInterface;

    /**
     * {@inheritdoc}
     *
     * @When /^(?:|I )confirm the alert$/
     */
    public function confirmAlert()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }

    /**
     * {@inheritdoc}
     *
     * @When /^(?:|I )cancel the alert$/
     */
    public function cancelAlert()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->dismiss_alert();
    }

    /**
     * {@inheritdoc}
     *
     * @Then /^(?:|I )should see an alert containing "(?P<expected>[^"]*)"$/
     */
    public function assertAlertMessage($expected)
    {
        $actual = $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();

        if (strpos($actual, $expected) === false) {
            throw new ExpectationException("Text '$expected' not found in alert", $this->getSession());
        }
    }

    /**
     * {@inheritdoc}
     *
     * @When /^(?:|I )fill "(?P<message>[^"]*)" into the prompt$/
     */
    public function setAlertText($message)
    {
        $this->getSession()->getDriver()->getWebDriverSession()->postAlert_text($message);
    }
}
