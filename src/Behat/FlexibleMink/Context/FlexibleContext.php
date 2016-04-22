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
    // Implements.
    use FlexibleContextInterface;

    // Depends.
    use ContainerContext;
    use JavaScriptContext;
    use SpinnerContext;
    use StoreContext;
    use TypeCaster;

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
}
