<?php


namespace XO\Player\Strategy;

use XO\Player\PlayerInterface;
use XO\Utilities\ChromePhp;
use XO\Utilities\TableHelper;

class GameLogic
{

    public $table;
    public $enemySymbol;
    public $mySymbol;
    protected $inverted = false;

    public function __construct($table, $symbol)
    {
        $this->setEnemySymbol($symbol);
        $this->setMySymbol($this->invertSymbol($symbol));
        $this->table = $table;
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

    public function defend()
    {
        return $this->getCoordinates();
    }

    public function attack()
    {
        return $this->attackCoordinates();
    }

    public function kill()
    {
        //killing is opposite to defend, so just invert symbol
        $this->invertSymbols(true);
        $move = $this->getCoordinates();

        //revert
        $this->invertSymbols(false);
        return $move;
    }

    public function random()
    {
        $x = rand(0, 2);
        $y = rand(0, 2);
        Utils::log(
            "I was dumb random!"
            . "col in $x, $y (hero . $this->mySymbol, enemy $this->enemySymbol));"
        );
        return array($x, $y);
    }

    protected function getCoordinates()
    {
        $action = $this->inverted ? 'Killing on' : 'Deffending on';

        $x = $this->defendLine('column');
        if (null !== $x) {
            $y = $this->getPossibleY($x);
            Utils::log("$action  col in $x, $y (hero . $this->mySymbol, enemy $this->enemySymbol)");
            return array($y, $x);
        }

        $y = $this->defendLine('row');
        if (null !== $y) {
            $x = $this->getPossibleX($y);
            Utils::log("$action row in $x, $y (hero . $this->mySymbol, enemy $this->enemySymbol)");
            return array($y, $x);
        }

        $defendCross = $this->defendCross();
        if ($this->isTurn($defendCross)) {
            Utils::log("$action  cross in $x, $y (hero . $this->mySymbol, enemy $this->enemySymbol)");
            return $defendCross;
        }
    }

    protected function defendCross()
    {
        $crossType = $this->getDefendedCross();

        if (isset($crossType)) {
            $cross = $this->getCrossLine($crossType);

            $x = $this->getEmptyField($cross);
            $y = $this->getCrossY($cross, $crossType);

            return [$x, $y];
        }
    }

    /**
     * @param $type
     *
     * @return int line index
     */
    protected function defendLine($type)
    {
        $count = 0;

        foreach ([0, 1, 2] as $index => $rowArray) {
            switch ($type) {
                case ('row'):
                    if (!$this->isFullRow($index)) {
                        $count = $this->countRow($index, 'enemy');
                    }
                    break;

                case ('column'):
                    if (!$this->isFullColumn($index)) {
                        $count = $this->countColumn($index, 'enemy');
                    }
                    break;
            }

            if ($count > 1) {
                return $index;
            }
        }
    }

    protected function getDefendedCross()
    {
        foreach ([true, false] as $crossRtl) {
            $cross = $this->getCrossLine($crossRtl);
            $count = $this->countBy($cross, 'enemy');

            if ($count > 1) {
                return $crossRtl;
            }
        }
    }

    private function attackCoordinates()
    {
        $attack = array(
            [1, 1],
            [0, 0]
        );
        foreach ($attack as $move) {
            list($x, $y) = $move;
            if ($this->isPossibleturn(array($x, $y))) {
                Utils::log("I atack! $x, $y");
                return [$x, $y];
            }
        }
    }

    protected function isDefendable($line)
    {
        return ($this->countBy($line, 'enemy')) == 2;
    }

    protected function isAttackable($line)
    {
        return ($this->countBy($line, 'my')) == 2;
    }
}

