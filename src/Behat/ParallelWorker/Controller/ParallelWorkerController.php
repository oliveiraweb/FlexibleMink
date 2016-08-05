<?php namespace Behat\ParallelWorker\Controller;

use Behat\Gherkin\Gherkin;
use Behat\Testwork\Cli\Controller;
use Behat\ParallelWorker\Filter\ParallelWorkerFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Configures the CLI options for the ParallelWorker extension.
 *
 * Based on the behat-partial-runner (http://github.com/m00t/behat-partial-runner) by Anton Serdyuk (moot).
 *
 * @author Taysir Tayyab
 */
class ParallelWorkerController implements Controller
{
    private $gherkin;

    /**
     * {@inheritdoc}
     */
    public function __construct(Gherkin $gherkin)
    {
        $this->gherkin = $gherkin;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Command $command)
    {
        $command
            ->addOption(
                '--total-workers',
                null,
                InputOption::VALUE_REQUIRED,
                "The total number of test nodes.",
                1
            )->addOption(
                '--current-worker',
                null,
                InputOption::VALUE_REQUIRED,
                "The number of the current test node (0-indexed).",
                0
            );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->gherkin->addFilter(
            new ParallelWorkerFilter(
                $input->getOption('total-workers'),
                $input->getOption('current-worker')
            )
        );
    }
}