<?php

use Behat\FlexibleMink\Context\AssertionContext;
use Behat\FlexibleMink\Context\FlexibleContext;
use Behat\FlexibleMink\Context\WebDownloadContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ExpectationException;

class FeatureContext extends FlexibleContext
{
    // Depends
    use AssertionContext;
    use WebDownloadContext;

    /**
     * Places an object with the given structure into the store.
     *
     * @Given the following is stored as :key:
     * @param string    $key        The key to put the object into the store under.
     * @param TableNode $attributes The attributes of the object to create.
     */
    public function putStoreStep($key, TableNode $attributes)
    {
        $this->put((object) ($attributes->getRowsHash()), $key);
    }

    /**
     * Asserts that an image finished loading.
     *
     * @Then I should see :imgSrc image in :locator
     *
     * @param  string               $imgSrc  The source of the image
     * @param  string               $locator The id of the image tag
     * @throws ExpectationException If the <img> tag is not found
     * @throws ExpectationException If the image is not loaded
     * @return true
     */
    public function assertImageLoaded($imgSrc, $locator)
    {
        $session = $this->getSession();
        $image = $session->getPage()->find('css', "img#$locator");

        if (!$image) {
            throw new ExpectationException("Expected an img tag with id '$locator'. Found none!", $session);
        }

        if ($image->getAttribute('src') != $imgSrc) {
            throw new ExpectationException("Expected src '$imgSrc'. Instead got '" . $image->getAttribute('src') . "'.", $session);
        }

        if (!$this->checkImageLoaded($image->getXpath())) {
            throw new ExpectationException("Expected img '$locator' to load. Instead it did not!", $session);
        }

        return true;
    }

    /**
     * Asserts that an image did NOT load.
     *
     * @Then I should not see an image in :locator
     *
     * @param  string               $locator The id of the image tag
     * @throws ExpectationException If the <img> tag is not found
     * @throws ExpectationException If the image is loaded
     * @return true
     */
    public function assertImageNotLoaded($locator)
    {
        $image = $this->getSession()->getPage()->find('css', "img#$locator");

        if (!$image) {
            throw new ExpectationException("Expected an img tag with id '$locator'. Found none!", $this->getSession());
        }

        if ($this->checkImageLoaded($image->getXpath())) {
            throw new ExpectationException("Expected img '$locator' to not load. Instead it did load!", $this->getSession());
        }

        return true;
    }
}
