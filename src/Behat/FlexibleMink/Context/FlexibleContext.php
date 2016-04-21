<?php

namespace Behat\FlexibleMink\Context;

use Behat\FlexibleMink\PseudoInterface\FlexibleContextInterface;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Overwrites some MinkContext step definitions to make them more resilient to failures caused by browser/driver
 * discrepancies and unpredictable load times.
 */
class FlexibleContext extends MinkContext
{
    use FlexibleContextInterface;
    use SpinnerContext;
    use StoreContext;
    use JavaScriptContext;

    /**
     * {@inheritdoc}
     */
    public function assertPageContainsText($text)
    {
        $this->waitFor(function () use ($text) {
            parent::assertPageContainsText($text);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function assertPageNotContainsText($text)
    {
        $this->waitFor(function () use ($text) {
            parent::assertPageNotContainsText($text);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function clickLink($locator)
    {
        $locator = $this->fixStepArgument($locator);

        $links = $this->getSession()->getPage()->findAll(
            'named',
            ['link', $this->getSession()->getSelectorsHandler()->xpathLiteral($locator)]
        );

        /** @var NodeElement $link */
        foreach ($links as $link) {
            try {
                $visible = $link->isVisible();
            } catch (UnsupportedDriverActionException $e) {
                $link->click();

                return;
            }

            if ($visible) {
                $link->click();

                return;
            }
        }

        throw new ExpectationException("No visible link found for '$locator'", $this->getSession());
    }

    /**
     * @Then /^I should see "([^"]*)" in the "([^"]*)" container$/
     */
    public function assertTextInContainer($text, $containerLabel)
    {
        $text = $this->injectStoredValues($text);
        $containerLabel = $this->injectStoredValues($containerLabel);
        $node = $this->getSession()->getPage()->find('xpath', "//*[contains(text(),'$containerLabel')]");
        if (!$node) {
            throw new ExpectationException("The $containerLabel container was not found", $this->getSession());
        }
        $containerId = $node->getAttribute('data-label-for');
        $container = $this->getSession()->getPage()->findById($containerId);

        if (!$container->find('xpath', "//*[contains(.,\"$text\")]")) {
            throw new ExpectationException("The $text was not found in the $containerLabel container", $this->getSession());
        }
    }
}
