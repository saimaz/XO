<?php


namespace XO\Player\Strategy;

use XO\Player\PlayerInterface;
use XO\Utilities\ChromePhp;
use XO\Utilities\TableHelper;

class Situation
{

    protected $table;
    protected $inverted = false;
    protected $enemySymbol;
    protected $mySymbol;

    public function __construct($table, $symbol)
    {
        $this->setEnemySymbol($symbol);
        $this->setMySymbol($symbol);
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->mySymbol;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    protected function isFullRow($rowIndex)
    {
        return $this->countRow($rowIndex) == 3;
    }

    protected function isFullColumn($rowIndex)
    {
        return $this->countColumn($rowIndex) == 3;
    }

    protected function countWithFilter($array, $filter)
    {
        if (!is_array($array)) {
            throw new \InvalidArgumentException('Count with filter needs array');
        }

        return count(
            array_filter(
                $array,
                array($this, $filter)
            )
        );
    }

    /**
     * Shortcut method for count with filter
     *
     * @param $array
     * @param $type
     *
     * @return int
     */
    protected function countBy($array, $type)
    {
        switch ($type)
        {
            case ('my'):
                return $this->countWithFilter($array, 'filterMyField');
                break;
            case ('enemy'):
                return $this->countWithFilter($array, 'filterEnemyField');
                break;
            default:
                return $this->countWithFilter($array, 'filterNonEmpty');
                break;
        }
    }

    protected function countColumn($index, $type = 'any')
    {
        $line = $this->getColumnLine($index);
        return $this->countBy($line, $type);
    }

    protected function countRow($index, $type = 'any')
    {
        $line = $this->getRowLine($index);
        return $this->countBy($line, $type);
    }

    protected function getPossibleY($index)
    {
        $items = $this->getColumnLine($index);
        return $this->getEmptyField($items);
    }

    protected function getPossibleX($index)
    {
        $items = $this->getRowLine($index);
        return $this->getEmptyField($items);
    }

    /**
     * @param $items
     *
     * @return int|string
     */
    protected function getEmptyField($items)
    {
        return array_search(null, $items);
    }

    protected function filterNonEmpty($field)
    {
        return $field !== null;
    }

    protected function filterEnemyField($field)
    {
        return $field == $this->enemySymbol;
    }

    protected function filterMyField($field)
    {
        return $field == $this->mySymbol;
    }

    protected function setMySymbol($symbol)
    {
        $this->mySymbol = $symbol;
    }

    protected function setEnemySymbol($symbol)
    {
        $this->enemySymbol = $symbol;
    }

    protected function invertSymbol($symbol)
    {
        return $symbol == PlayerInterface::SYMBOL_X
        ? PlayerInterface::SYMBOL_O : PlayerInterface::SYMBOL_X;
    }

    protected function isPossibleturn($move)
    {
        if (!$this->isTurn($move)) {
            return false;
        }
        list($x, $y) = $move;

        $table = $this->getTable();
        if (isset($table[$x][$y])) {

            return false;
        } else {
            return true;
        }
    }

    public function isTurn($move)
    {
        if (!is_array($move)) {
            return false;
        }

        $turn = array_filter(
            $move,
            function ($x) {
                return $x !== null;
            }
        );

        if (count($turn) == 2) {
            return true;
        }

        return false;
    }

    /**
     * @param $index
     *
     * @return array
     */
    protected function getColumnLine($index)
    {
        $items = $this->getTableHelper()->getColumn($index);
        return $items;
    }

    /**
     * @param $index
     *
     * @return array
     */
    protected function getRowLine($index)
    {
        $items = $this->getTableHelper()->getRow($index);
        return $items;
    }

    protected function getCrossLine($rtl)
    {

        $items = $this->getTableHelper()->getCross($rtl);
        return $items;
    }

    protected function getPossibleMoves()
    {
        return $this->getTableHelper()->getPossibleMoves();
    }

    protected function getTableHelper()
    {
        return new TableHelper($this->getTable());
    }


    protected function getCrossY($cross, $crossRtl)
    {
        if ($crossRtl === true) {
            $cross = array_reverse($cross);
        }

        return $this->getEmptyField($cross);
    }

    protected function invertSymbols($inverted = null)
    {
        $this->setEnemySymbol($this->invertSymbol($this->enemySymbol));
        $this->setMySymbol($this->invertSymbol($this->mySymbol));
        if (isset($inverted)) {
            $this->inverted = $inverted;
        }
    }
}

