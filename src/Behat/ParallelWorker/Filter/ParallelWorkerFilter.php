<?php namespace Behat\ParallelWorker\Filter;

use Behat\Gherkin\Filter\SimpleFilter;
use Behat\Gherkin\Node\ExampleTableNode;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Gherkin\Node\ScenarioInterface;

/**
 * A scenario filter which filters on individual scenarios and outlines. Usefull for separating tests onto multiple
 * worker nodes.
 *
 * Based on the behat-partial-runner (http://github.com/m00t/behat-partial-runner) by Anton Serdyuk (moot).
 *
 * @author Taysir Tayyab
 */
class ParallelWorkerFilter extends SimpleFilter
{
    private $totalNodes;
    private $curNode;
    private $curScenario;

    /**
     * {@inheritdoc}
     */
    public function __construct($totalNodes = 1, $curNode = 0)
    {
        $this->totalNodes= $totalNodes;
        $this->curNode = $curNode;
        $this->curScenario = -1;
    }

    /**
     * {@inheritdoc}
     */
    public function filterFeature(FeatureNode $feature)
    {
        $scenarios = [];

        // loop through each scenario in this feature file
        foreach ($feature->getScenarios() as $scenario)
        {
            // if this is a scenario outline, we need to look at each example
            if ($scenario instanceof OutlineNode && $scenario->hasExamples()) {
                $table = $scenario->getExampleTable()->getTable();
                $lines = array_keys($table);

                // filtered table will hold the the examples which will run on this node
                $filteredTable = [];

                // okay so each outline example is treated like its own scenario, meaning we start at the offset of the
                // current scenario counter and incrementing by the node total
                for($i = ($this->curScenario++ % $this->totalNodes); $i < count($lines); $i += $this->totalNodes) {
                    $filteredTable[] = [$lines[$i], $table[$lines[$i]]];
                }

                // create a new scenario with just the filtered examples (if any were left)
                if(count($filteredTable)) {
                    $scenario = new OutlineNode(
                        $scenario->getTitle(),
                        $scenario->getTags(),
                        $scenario->getSteps(),
                        new ExampleTableNode($filteredTable, $scenario->getExampleTable()->getKeyword()),
                        $scenario->getKeyword(),
                        $scenario->getLine()
                    );
                } else {
                    // if there were no examples to run, skip this scenario
                    continue;
                }
            } elseif($this->curScenario++ % $this->totalNodes != 0) {
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
