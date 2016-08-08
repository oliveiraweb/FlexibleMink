<?php namespace Behat\ParallelWorker\Filter;

use Behat\Gherkin\Filter\SimpleFilter;
use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioInterface;
use InvalidArgumentException;
use RuntimeException;

/**
 * A scenario filter which filters on individual scenarios and outlines. Usefull for separating tests onto multiple
 * worker nodes.
 *
 * Based on the behat-partial-runner (http://github.com/m00t/behat-partial-runner) by Anton Serdyuk (m00t).
 *
 * @author Taysir Tayyab
 */
class ParallelWorkerFilter extends SimpleFilter
{
    private $totalNodes;
    private $curNode;
    private $curScenario;

    /**
     * This method takes an example table for a scenario and filters it according to the total number of nodes. Each
     * example is treated like it's own scenario as far as counting goes for the workers.
     *
     * @param  ExampleTableNode $examples The examples of the Scenario Outline
     * @throws RuntimeException If there are no examples in this outline which will run on this node
     * @return ExampleTableNode A filtered table leaving only examples that should run on this node
     */
    private function filterExampleNode(ExampleTableNode $examples)
    {
        // $offset represents the index of the first element in the table we would collect for this node
        $curr = $this->curScenario % $this->totalNodes;
        $offset = ($this->totalNodes - $curr) % $this->totalNodes;

        // get the examples as array and pull off the first row (which are the headers)
        $table = array_values($examples->getTable());
        $filteredTable = [array_shift($table)];

        // if the table is long enough, then grab an example
        if ($offset < count($table)) {
            for ($i = $offset; $i < count($table); $i += $this->totalNodes) {
                $filteredTable[] = $table[$i];
            }
        } else {
            // technically we don't HAVE to throw an exception here, because this is to a certain extent expected
            // but its nice because now @return can just be ExampleTableNode
            throw new RuntimeException('No examples will run on this node!');
        }

        $this->curScenario += count($table);

        return new ExampleTableNode($filteredTable, $examples->getKeyword());
    }

    /**
     * {@inheritdoc}
     */
    public function __construct($curNode = 0, $totalNodes = 1)
    {
        if ($totalNodes <= 0 || $curNode < 0 || $curNode >= $totalNodes) {
            throw new InvalidArgumentException("Received bad arguments for (\$curNode, \$totalNodes): ($curNode, $totalNodes).");
        }

        $this->totalNodes = $totalNodes;
        $this->curNode = $curNode;
        $this->curScenario = $this->totalNodes - $this->curNode;
    }

    /**
     * {@inheritdoc}
     */
    public function filterFeature(FeatureNode $feature)
    {
        $scenarios = [];

        // loop through each scenario in this feature file
        foreach ($feature->getScenarios() as $scenario) {
            // if this is a scenario outline, we need to look at each example
            if ($scenario instanceof OutlineNode && $scenario->hasExamples()) {
                try {
                    // filter to just the ones that will run on this node
                    $filteredExampleTable = $this->filterExampleNode($scenario->getExampleTable());
                } catch (RuntimeException $e) {
                    $filteredExampleTable = [];
                }

                if ($filteredExampleTable) {
                    // if there are examples this node can run, recreate the scenario with just the filtered examples
                    $scenario = new OutlineNode(
                        $scenario->getTitle(),
                        $scenario->getTags(),
                        $scenario->getSteps(),
                        $filteredExampleTable,
                        $scenario->getKeyword(),
                        $scenario->getLine()
                    );
                } else {
                    // if there were no examples to run, skip this scenario
                    continue;
                }
            } elseif ($this->curScenario++ % $this->totalNodes != 0) {
                // for regular scenarios, if its not our turn yet, then skip and increment the counter
                continue;
            }

            $scenarios[] = $scenario;
        }

        return new FeatureNode(
            $feature->getTitle(),
            $feature->getDescription(),
            $feature->getTags(),
            $feature->getBackground(),
            $scenarios,
            $feature->getKeyword(),
            $feature->getLanguage(),
            $feature->getFile(),
            $feature->getLine()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isFeatureMatch(FeatureNode $feature)
    {
        // we don't want to filter by feature, we want to filter by scenario, so always return false
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isScenarioMatch(ScenarioInterface $scenario)
    {
        // we do the filtering up in filterFeature, so always return true
        return true;
    }
}
