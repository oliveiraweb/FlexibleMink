<?php

namespace features\Extensions\Assertion;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Behat\Tester\ServiceContainer\TesterExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * A helper extension to load the StepTester into an AssertionContext.
 */
class AssertionExtension implements Extension
{
    public const TESTER_INJECTOR_TAG = 'assertion.injector';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Do nothing.
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'assertion';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        // Do nothing.
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        // Do nothing.
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition(
            'features\Extensions\Assertion\AssertionInitializer',
            [new Reference(TesterExtension::STEP_TESTER_ID)]
        );

        $definition->addTag(ContextExtension::INITIALIZER_TAG);

        $container->setDefinition(
            self::TESTER_INJECTOR_TAG,
            $definition
        );
    }
}
