<?php namespace Behat\FlexibleMink\Context;

use Behat\FlexibleMink\PseudoInterface\FlexibleContextInterface;
use Behat\FlexibleMink\PseudoInterface\TableContextInterface;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use InvalidArgumentException;

/**
 * Class TableContext.
 */
trait TableContext
{
    // Depends.
    use FlexibleContextInterface;

    // Implements.
    use TableContextInterface;

    /**
     * Finds a table with a given name or id using partial matching.
     *
     * @param  string                   $name The name of the table. Will be matched against id or name properties.
     * @throws ElementNotFoundException If not table is found with id or name {@paramref $name}
     * @return NodeElement              The matched table
     */
    private function findNamedTable($name)
    {
        $idPiece = "contains(normalize-space(@id), '$name')";
        $namePiece = "contains(normalize-space(@name), '$name')";

        /** @var NodeElement $table */
        $table = $this->assertSession()->elementExists('xpath', "//table[$idPiece or $namePiece]");

        if (!$table) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'table', 'xpath', 'id, name');
        }

        return $table;
    }

    /**
     * This method will retrieve a table by its name. If the table is stored in the key store, that will be used,
     * otherwise a fresh parse will be done against the table's HTML. Setting {@param $forceFresh} to true will
     * ignore the key store and build the table from HTML.
     *
     * @param  string $name       The name of the table to be used in an xpath query
     * @param  bool   $forceFresh Setting to true will rebuild the table from HTML and not use the store
     * @return array  An array containing parsed rows and cells as returned form $this->buildTableFromHtml
     */
    private function getTableFromName($name, $forceFresh = false)
    {
        // retrieve table from the store if it exists there
        if ($this->isStored($name) && !$forceFresh) {
            return $this->get($name);
        }

        // find the table node and parse it's contents
        $table = $this->findNamedTable($name);
        $tableData = $this->buildTableFromHtml($table);

        $this->put($tableData, $name);

        return $tableData;
    }

    /**
     * Retrieves a row from the table HEAD (thead) that has no cells with a colspan property. This row is assumed to be
     * the column "titles".
     *
     * @param  NodeElement              $table The table to find the columns for
     * @throws InvalidArgumentException If {@paramref $table} is not an instance of NodeElement
     * @throws ElementNotFoundException If no HEAD (thead) rows are found in {@paramref $table}
     * @throws ElementNotFoundException If all head rows have a td/th with colspan property
     * @throws ElementNotFoundException If the row with no colspann'd td/th tags has no td/th at all
     * @return NodeElement[]            The columns for {@paramref $table}
     */
    private function findHeadColumns($table)
    {
        if (!($table instanceof NodeElement)) {
            throw new InvalidArgumentException('Parameter $table must be an instance of NodeElement.');
        }

        /** @var NodeElement[] $rows */
        $rows = $table->findAll('xpath', '/thead/tr');

        if (!$rows) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'tr', 'xpath');
        }

        $colRow = null;
        foreach ($rows as $row) {
            // finds all td|th elements that have a colspan tag. We do this because we don't want any HEAD rows
            // when some of the cells span multiple columns
            /** @var NodeElement[] $splitCell */
            $splitCell = $row->findAll('xpath', '/*[@colspan and (self::td or self::th)]');

            if (!$splitCell) {
                $colRow = $row;
                break;
            }
        }

        // we couldn't find a HEAD row that didn't have split columns
        if (!$colRow) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'tr/(td or th)', 'xpath', 'not(@colspan)');
        }

        // get all the cells in the selected row
        /** @var NodeElement[] $columns */
        $columns = $colRow->findAll('xpath', '/*[self::td or self::th]');

        if (!$columns) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'td or th', 'xpath');
        }

        return $columns;
    }

    /**
     * @param  NodeElement $table The HTML table to parse
     * @param  string      $name  The name of the table, for storing in the key store
     * @return array       Returns an array with the following form:
     *                           colHeaders => the best "guess" for column titles
     *                           head => [row][column] Cells parsed from the thead section of the table
     *                           body => [row][column] Cells parsed from the tbody section of the table
     *                           foot => [row][column] Cells parsed from the tfoot section of the table
     */
    private function buildTableFromHtml($table, $keyName = '')
    {
        $colTitles = array_map(function ($ele) {
            return trim($ele->getText());
        }, $this->findHeadColumns($table));

        $colTitles = array_filter($colTitles, function ($ele) {
            return (bool) $ele;
        });

        /* @var NodeElement[] $rows */
        $headRows = $table->findAll('xpath', '/thead/tr');
        $bodyRows = $table->findAll('xpath', '/tbody/tr');
        $footRows = $table->findAll('xpath', '/tfoot/tr');

        /*
         * Anonymous function to retrieve cell values from an array of row nodes. Does not support row and colspans!
         *
         * @param NodeElement[] $rows The rows to parse
         * @return array The cell values for the rows numerically indexed as [row][col]
         */
        $parser = function ($rows) {
            $data = [];

            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                /** @var NodeElement[] $cells */
                $cells = $row->findAll('xpath', '/td|/th');

                for ($j = 0; $j < count($cells); $j++) {
                    $cell = $cells[$j];
                    $data[$i][$j] = trim($cell->getText());
                }
            }

            return $data;
        };

        // build the table array with parsed cells
        $tableData = [
            'colHeaders' => $colTitles,
            'head'       => $headRows ? $parser($headRows) : [],
            'body'       => $parser($bodyRows),
            'foot'       => $footRows ? $parser($footRows) : [],
        ];

        // if a key name was provided, we can store this array for quick access next time
        if ($keyName) {
            $this->put($tableData, $keyName);
        }

        return $tableData;
    }

    /**
     * This method returns the value of a particular cell from a parsed table.
     *
     * @param array  $table A table array as returned by $this->buildTableFromHtml
     * @param int    $rIdx  The row index of the cell to retrieve
     * @param int    $cIdx  The col index of the cell to retrieve
     * @param string $piece Must be one of (head, body, foot). Specifies which section of the table to look in
     */
    private function getCellFromTable($table, $rIdx, $cIdx, $piece = 'body')
    {
        if (!in_array($piece, ['head', 'body', 'foot'])) {
            throw new InvalidArgumentException('$piece must be one of (head, body, foot)!');
        }

        if (count($table[$piece]) < $rIdx) {
            throw new ExpectationException("The row index $rIdx for the table is out of bounds. Table has " .
                count($table[$piece]) . ' rows.',
                $this->getSession());
        }

        if (count($table[$piece][$rIdx - 1]) < $cIdx) {
            throw new ExpectationException("The col index $cIdx for the table is out of bounds. Table has " .
                count($table[$piece][$rIdx - 1]) . ' cols.',
                $this->getSession());
        }

        return $table[$piece][$rIdx - 1][$cIdx - 1];
    }

    /**
     * {@inheritdoc}
     *
     * @Then the table :name is updated
     */
    public function refreshTable($name)
    {
        return $this->getTableFromName($name, true);
    }

    /**
     * {@inheritdoc}
     *
     * @Given I have a table :name
     * @Then I should see table :name
     */
    public function assertTableExists($name)
    {
        try {
            $table = $this->findNamedTable($name);
        } catch (ElementNotFoundException $e) {
            throw new ExpectationException("Could not find table with name '$name'.", $this->getSession());
        }

        if (is_array($table) && count($table) != 1) {
            throw new ExpectationException("Found multiple tables with name '$name'.", $this->getSession());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @Given the table :name has 1 row
     * @Given the table :name has :num rows
     * @Then the table :name should have 1 row
     * @Then the table :name should have :num rows
     */
    public function assertTableHasRows($name, $num = 1, $fullTable = false)
    {
        if (!is_numeric($num) || $num < 0 || (int) $num != $num) {
            throw new InvalidArgumentException('Number of rows must be an integer greater than 0.');
        }

        $table = $this->getTableFromName($name);

        $rowCount = count($table['body']);

        if ($fullTable) {
            $rowCount += count($table['head']) + count($table['body']);
        }

        if ($rowCount != $num) {
            throw new ExpectationException("Expected $num rows for table '$name'. Instead got $rowCount.", $this->getSession());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @Given the table :name has 1 column
     * @Given the table :name has :num columns
     * @Then the table :name should have 1 column
     * @Then the table :name should have :num columns
     */
    public function assertTableHasColumns($name, $num = 1)
    {
        if (!is_numeric($num) || $num < 0 || (int) $num != $num) {
            throw new InvalidArgumentException('Number of rows must be an integer greater than 0.');
        }

        $table = $this->getTableFromName($name);
        $colCount = count($table['body'][0]);

        if ($colCount != $num) {
            throw new ExpectationException("Expected $num columns for table '$name'. Instead got $colCount.", $this->getSession());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @Given the table :name has the following column titles:
     * @Then the table :name should have the following column titles:
     */
    public function assertTableColumnTitles(TableNode $attributes, $name)
    {
        $expectedCols = array_map(function ($ele) {
            return $ele[0];
        }, $attributes->getRows());

        $table = $this->getTableFromName($name);

        $remainingCols = $expectedCols;

        foreach ($table['colHeaders'] as $colText) {
            if ($colText && !in_array($colText, $expectedCols)) {
                throw new ExpectationException("Found column title $colText, but was not expecting it.", $this->getSession());
            }

            $remainingCols = array_diff($remainingCols, [$colText]);
        }

        if ($remainingCols) {
            throw new ExpectationException("Did not find matches for '" . explode($remainingCols, ',') . "'.", $this->getSession());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @Given /^the table (?P<name>"[^"]+") has (?P<val>"[^"]+") at \((?P<rIdx>\d+),(?P<cIdx>\d+)\) in the header$/
     * @Then /^the table (?P<name>"[^"]+") should have (?P<val>"[^"]+") at \((?P<rIdx>\d+),(?P<cIdx>\d+)\) in the header$/
     */
    public function assertCellValueHead($name, $val, $rIdx, $cIdx)
    {
        $table = $this->getTableFromName($name);
        $cellVal = $this->getCellFromTable($table, $rIdx, $cIdx, 'head');

        if ($cellVal != $val) {
            throw new ExpectationException("Expected $val at ($rIdx, $cIdx). Instead got $cellVal!", $this->getSession());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @Given /^the table (?P<name>"[^"]+") has (?P<val>"[^"]+") at \((?P<rIdx>\d+),(?P<cIdx>\d+)\) in the body$/
     * @Then /^the table (?P<name>"[^"]+") should have (?P<val>"[^"]+") at \((?P<rIdx>\d+),(?P<cIdx>\d+)\) in the body$/
     */
    public function assertCellValueBody($name, $val, $rIdx, $cIdx)
    {
        $table = $this->getTableFromName($name);
        $cellVal = $this->getCellFromTable($table, $rIdx, $cIdx);

        if ($cellVal != $val) {
            throw new ExpectationException("Expected $val at ($rIdx, $cIdx). Instead got $cellVal!", $this->getSession());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @Given /^the table (?P<name>"[^"]+") has (?P<val>"[^"]+") at \((?P<rIdx>\d+),(?P<cIdx>\d+)\) in the footer$/
     * @Then /^the table (?P<name>"[^"]+") should have (?P<val>"[^"]+") at \((?P<rIdx>\d+),(?P<cIdx>\d+)\) in the footer$/
     */
    public function assertCellValueFoot($name, $val, $rIdx, $cIdx)
    {
        $table = $this->getTableFromName($name);
        $cellVal = $this->getCellFromTable($table, $rIdx, $cIdx, 'foot');

        if ($cellVal != $val) {
            throw new ExpectationException("Expected $val at ($rIdx, $cIdx). Instead got $cellVal!", $this->getSession());
        }

        return true;
    }
}
