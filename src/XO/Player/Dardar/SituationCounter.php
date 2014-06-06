<?php


namespace XO\Player\Dardar;

use XO\Player\Dardar\Move\Move;
use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\Move\MoveInterface;
use XO\Player\Dardar\Move\NullMove;
use XO\Player\PlayerInterface;
use XO\Utilities\TableHelper;

class SituationCounter
{
    public $table;
    public $enemySymbol;
    public $mySymbol;
    public $pandoraRow;
    public $pandoraColumn;
    protected $inverted = false;
    protected $edgeMove = array();

    const ME = 'my';
    const HE = 'enemy';
    const EMPTIES = 'empty';
    const NONEMPTY = 'nonempty';

    public function __construct($table, $symbol)
    {
        $this->setSymbols($symbol);
        $this->table = $table;
    }


    /**
     * Sets symbols by my symbol
     * @param $symbol - My symbol
     */
    public function setSymbols($symbol)
    {
        $this->setMySymbol($symbol);
        $this->setEnemySymbol($this->invertSymbol($symbol));
    }

    public function setSymbolsIfMissing($symbol)
    {
        if (null === $this->me() && null === $this->he()) {
            $this->setSymbols($symbol);
        }
    }

    protected function invertSymbol($symbol)
    {
        return $symbol == PlayerInterface::SYMBOL_X
            ? PlayerInterface::SYMBOL_O : PlayerInterface::SYMBOL_X;
    }

    protected function setEnemySymbol($symbol)
    {
        $this->enemySymbol = $symbol;
    }

    protected function setMySymbol($symbol)
    {
        $this->mySymbol = $symbol;
    }

    protected function getTableHelper()
    {
        return new TableHelper($this->getTable());
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    protected function getPossibleMoves()
    {
        return $this->getTableHelper()->getPossibleMoves();
    }

    /**
     * @param $index
     *
     * @return array
     */
    public function getRowLine($index)
    {
        $items = $this->getTableHelper()->getRow($index);
        return $items;
    }

    public function getCrossLine($rtl)
    {
        $items = $this->getTableHelper()->getCross($rtl);

        if ($rtl) {
            $items = array_reverse($items);
        }

        return $items;
    }

    /**
     * Return cross bool $rtl on success, null on fail
     * Callbacks will return booleans!
     * @param $callback
     * @return boolean|null Returns cross type on success, null on fail
     */
    protected function findInCrosses($callback)
    {
        $args = func_get_args();

        foreach ([false, true] as $crossRtl) {
            $cross = $this->getCrossLine($crossRtl);
            if (method_exists($this, $callback)) {
                if (func_num_args() > 1) {

                    $args[0] = $cross;
                    if (call_user_func_array(array($this, $callback), $args)) {
                        return $crossRtl;
                    }
                }

                if ($this->$callback($cross)) {
                    return $crossRtl;
                }
            }
        }
    }

    protected function findDefendedCross()
    {
        return $this->findInCrosses('isDefendable');
    }

    public function findInLine($symbol, $line)
    {
        return array_search($symbol, $line, true);
    }

    public function hasPattern($line, $pattern)
    {
        return $line == $pattern || $line == array_reverse($pattern);
    }

    protected function isDefendable($line)
    {
        return $this->countBy($line, self::HE) == 2 && $this->countBy($line, self::EMPTIES) == 1;
    }

    public function getCorners()
    {
        return array(
            [0, 0],
            [0, 2],
            [2, 0],
            [2, 2]
        );
    }

    /**
     * @param $index
     *
     * @return array
     */
    public function getColumnLine($index)
    {
        $items = $this->getTableHelper()->getColumn($index);
        return $items;
    }

    public function countMoves()
    {
        return 9 - count($this->getPossibleMoves());
    }

    /**
     * Shortcut public method for count with filter
     *
     * @param $array
     * @param $type
     *
     * @return int
     * @throws \InvalidArgumentException
     */
    public function countBy($array, $type)
    {
        switch ($type) {
            case (self::ME):
                $filtered = $this->countWithFilter($array, 'filterMyField');
                break;
            case (self::HE):
                $filtered = $this->countWithFilter($array, 'filterEnemyField');
                break;
            case (self::EMPTIES):
                $filtered = $this->countWithFilter($array, 'filterEmpty');
                break;
            case (self::NONEMPTY):
                $filtered = $this->countWithFilter($array, 'filterNonEmpty');
                break;
            default:
                throw new \InvalidArgumentException('No valid type set');
        }

        return $filtered;
    }

    public function isCrossAttackPattern($line)
    {
        $pattern = [$this->he(), $this->me(), $this->he()];
        return $this->hasPattern($line, $pattern);
    }

    public function isCrossAttackable($line)
    {
        $pattern = [$this->me(), $this->he(), null];
        return $this->hasPattern($line, $pattern);
    }

    protected function isCornerAttackable($line)
    {
        $pattern = [null, $this->me(), $this->he()];
        return $this->hasPattern($line, $pattern);
    }

    public function findCornerAttackableCross()
    {
        return $this->findInCrosses('isCornerAttackable');
    }

    public function findCrosAttackableCross()
    {
        return $this->findInCrosses('isCrossAttackable');
    }


    public function findSymbolCoordsInCross($line, $type, $symbol)
    {
        $x = array_search($symbol, $line);
        $y = $this->getCrossY($line, $type, $symbol);

        if (is_int($x) && is_int($y)) {
            return [$x, $y];
        }
    }

    protected function filterEmpty($field)
    {
        return $field === null;
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

    public function countTableMovesBy($symbol)
    {
        $out = $this->findAllSymbolCoords($symbol);
        return count($out);
    }

    public function getSymbolCoordsInCross($line, $type, $symbol)
    {
        $x = array_search($symbol, $line);
        $y = $this->getCrossY($line, $type, $symbol);

        if (is_int($x) && is_int($y)) {
            return [$x, $y];
        }
    }

    protected function getCrossY($cross, $crossRtl, $symbol = null)
    {
        if ($crossRtl === true) {
            $cross = array_reverse($cross);
        }

        return array_search($symbol, $cross);
    }

    /**
     * @param $symbol
     *
     * @return array
     */
    private function findAllSymbolCoords($symbol)
    {
        $out = [];

        foreach ($this->table as $row => $data) {
            foreach ($data as $col => $value) {
                if ($value === $symbol) {
                    $out[] = [$row, $col];
                }
            }
        }
        return $out;
    }

    public function isCoordsNeighbours($move1, $move2)
    {
        if (!is_array($move1) || !is_array($move2)) {
            throw new \InvalidArgumentException(
                'Not valid moves received '
                . "\n" . var_export($move1, true)
                . "\n" . var_export($move2, true)
            );
        }

        list($x1, $y1) = $move1;
        list($x2, $y2) = $move2;

        if ($x1 == $x2) {
            return $this->areValuesNear($y1, $y2);
        }

        if ($y1 == $y2) {
            return $this->areValuesNear($x1, $x2);
        }
        return false;
    }


    public function areValuesNear($x, $y)
    {
        return ($x + $y) % 2 == 1;
    }

    public function randomMove()
    {
        $moves = $this->getPossibleMoves();
        shuffle($moves);
        return $moves[0];
    }

    public function me()
    {
        return $this->mySymbol;
    }

    public function he()
    {
        return $this->enemySymbol;
    }

    public function getInfo()
    {
        return "\nhero . $this->mySymbol, enemy $this->enemySymbol .\n"
        . json_encode($this->getTable());
    }


    public function isCoordinatedEmpty($coordinates)
    {
        list($x, $y) = $coordinates;

        $table = $this->getTable();
        if (isset($table[$x][$y])) {

            return false;
        } else {
            return true;
        }
    }
}
