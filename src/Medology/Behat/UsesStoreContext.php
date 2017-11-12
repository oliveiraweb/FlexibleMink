<?php namespace Medology\Behat;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use RuntimeException;

/**
 * Trait that grants access to the StoreContext instance via $this->storeContext.
 */
trait UsesStoreContext
{
    /** @var StoreContext */
    protected $storeContext;

    /**
     * Gathers the StoreContext instance and stores a reference in $this->storeContext.
     *
     * @param  BeforeScenarioScope $scope
     * @throws RuntimeException    If the current environment is not initialized.
     * @return void
     * @BeforeScenario
     */
    public function gatherStoreContext(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        if (!($environment instanceof InitializedContextEnvironment)) {
            throw new RuntimeException(
                'Expected Environment to be ' . InitializedContextEnvironment::class .
                ', but got ' . get_class($environment)
            );
        }

        if (!$this->storeContext = $environment->getContext(StoreContext::class)) {
            throw new RuntimeException('Failed to gather StoreContext');
        }
    }
}
