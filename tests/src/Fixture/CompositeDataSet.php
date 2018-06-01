<?php

namespace Test\Fixture;

use PHPUnit\DbUnit\DataSet\AbstractDataSet;
use PHPUnit\DbUnit\DataSet\DefaultTableIterator;
use PHPUnit\DbUnit\DataSet\IDataSet;
use Psr\Log\InvalidArgumentException;

class CompositeDataSet extends AbstractDataSet
{
    private $dataSets = [];

    public function addDataSet(IDataSet $dataSet)
    {
        foreach ($dataSet->getTableNames() as $tableName)
        {
            if (in_array($tableName, $this->getTableNames()))
            {
                throw new InvalidArgumentException("DataSet contains a table that already exists: {$tableName}");
            }
        }

        $this->dataSets[] = $dataSet;
    }

    public function getDataSet()
    {
        return $this->dataSets;
    }

    protected function createIterator($reverse = FALSE)
    {
        $iterator = new \AppendIterator();
        $dataSets = $reverse ? array_reverse($this->dataSets) : $this->dataSets;
        foreach ($dataSets as $dataSet)
        {
            $dataSetIterator = $reverse ? $dataSet->getReverseIterator() : $dataSet->getIterator();
            $iterator->append($dataSetIterator);
        }
        return $iterator;
    }
}