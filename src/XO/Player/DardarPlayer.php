<?php

namespace XO\Player;

//use XO\Utilities\ChromePhp;
use XO\Utilities\TableHelper;

/**
 * This class provides not very smart player ;)
 */
class DardarPlayer implements PlayerInterface
{

    protected $symbol;

    /**
     * @inheritdoc
     */
    public function turn($table, $symbol = self::SYMBOL_X)
    {
        $this->setEnemySymbol($symbol);
        //ChromePhp::log($table);

        //Always start from middle
        $x = $y = 1;
        while ($table[$x][$y] !== null) {
            list($x, $y) = $this->getDefendCoordinates($table);
        }

        return [$x, $y];
    }

    protected function isFullRow($table, $rowIndex){
        return $this->countRow($table, $rowIndex, true) > 2;
    }

    protected function countRow($table, $rowIndex, $any = false) {
        $table = new TableHelper($table);
        if ($any) {
            $items = count(array_filter($table->getRow($rowIndex), function ($x) {return $x !== null;}));
        } else {
            $items = count(array_filter($table->getRow($rowIndex), function ($x) {return $x == $this->symbol;}));
        }
        return $items;

    }

    protected function isFullColumn($table, $rowIndex){
        return $this->countColumn($table, $rowIndex, true) > 2;
    }

    protected function countColumn($table, $rowIndex, $any = false) {
        $table = new TableHelper($table);
        if ($any) {
            $items = count(array_filter($table->getColumn($rowIndex), function ($x) {return $x !== null;}));
        } else {
            $items = count(array_filter($table->getColumn($rowIndex), function ($x) {return $x == $this->symbol;}));
        }
        return $items;
    }

    /**
     * @param $table
     * @param $type
     *
     * @return int line index
     */
    protected function defendLine($table, $type){
        $count = 0;

        foreach ([0, 1, 2] as $index  => $rowArray) {
            switch ($type)
            {
                case('row'):
                if (!$this->isFullRow($table, $index))
                {
                    $count = $this->countRow($table, $index);
                }
                break;

                case('column'):
                if (!$this->isFullColumn($table, $index))
                {
                    $count = $this->countColumn($table, $index);
                }
                break;
            }

            if ($count > 1) {
                return $index;
            }
        }
    }

    protected function getDefendCoordinates($table) {

        //$this->tryToKill();
        //$this->defendCrosses();

        $x = $this->defendLine($table, 'column');
        if (null !== $x) {
            $y = $this->getPossibleY($table, $x);
            return array($y, $x);
        }

        $y = $this->defendLine($table, 'row');
        if (null !== $y) {
            $x = $this->getPossibleX($table, $y);
            return array($y, $x);
        }

        return $this->getRandomCoords();
    }

    protected function getRandomCoords(){
         $x = rand(0, 2);
         $y = rand(0, 2);
        //ChromePhp::log('I was dumb random!');
        return array($x, $y);
    }

    private function setEnemySymbol($symbol)
    {
        return $this->symbol = $symbol;
        return $this->symbol = $symbol == self::SYMBOL_X ? self::SYMBOL_O : self::SYMBOL_X;
    }

    private function getPossibleY($table, $index)
    {
        $table = new TableHelper($table);
        $items = $table->getColumn($index);
        return $this->getEmptyField($items);
    }

    private function getPossibleX($table, $index)
    {
        $table = new TableHelper($table);
        $items = $table->getRow($index);
        return $this->getEmptyField($items);
    }

    /**
     * @param $items
     *
     * @return int|string
     */
    private function getEmptyField($items)
    {
        foreach ($items as $index => $value) {
            if ($value === null) {
                return $index;
            }
        }
    }
}
