<?php

namespace Behat\FlexibleMink\Context;

use Behat\FlexibleMink\PseudoInterface\StoreContextInterface;
use Exception;

/**
 * {@inheritdoc}
 */
trait StoreContext
{
    // Implements.
    use StoreContextInterface;

    /** @var array */
    protected $registry;

    /**
     * Clears the registry before each Scenario to free up memory and prevent access to stale data.
     *
     * @BeforeScenario
     */
    public function clearRegistry()
    {
        $this->registry = [];
    }

    /**
     * {@inheritdoc}
     */
    protected function put($thing, $key)
    {
        $this->registry[$key][] = $thing;
    }

    /**
     * {@inheritdoc}
     */
    protected function assertIsStored($key, $nth = null)
    {
        if (!$thing = $this->isStored($key, $nth)) {
            throw new Exception("Entry $nth for $key was not found in the store.");
        }

        return $thing;
    }

    /**
     * {@inheritdoc}
     */
    protected function get($key, $nth = null)
    {
        if (!$nth && preg_match('/^([1-9][0-9]*)(?:st|nd|rd|th) (.+)$/', $key, $matches)) {
            $nth = $matches[1];
            $key = $matches[2];
        }

        if (!$this->isStored($key, $nth)) {
            return;
        }

        return $nth ? $this->registry[$key][$nth - 1] : end($this->registry[$key]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getThingProperty($key, $property, $nth = null)
    {
        $thing = $this->assertIsStored($key, $nth);

        if (isset($thing, $property)) {
            return $thing->$property;
        }

        throw new Exception("'$thing' existed in the store but had no '$property' property.'");
    }

    /**
     * {@inheritdoc}
     */
    protected function injectStoredValues($string)
    {
        preg_match_all('/\(the ([^\)]+) of the ([^\)]+)\)/', $string, $matches);
        foreach ($matches[0] as $i => $match) {
            $thingName = $matches[2][$i];
            $thingProperty = $matches[1][$i];

            if (!$thing = $this->get($thingName)) {
                throw new Exception("Did not find $thingName in the store");
            }

            if (!isset($thing, $thingProperty)) {
                throw new Exception("$thingName does not have a $thingProperty property");
            }

            $string = str_replace($match, $thing->$thingProperty, $string);
        }

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    protected function isStored($key, $nth = null)
    {
        return $nth ? isset($this->registry[$key][$nth - 1]) : isset($this->registry[$key]);
    }
}
