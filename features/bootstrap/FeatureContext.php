<?php

use Behat\FlexibleMink\Context\FlexibleContext;
use Behat\Gherkin\Node\TableNode;

class FeatureContext extends FlexibleContext
{
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
}
