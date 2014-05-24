<?php


namespace XO\Player\Dardar;

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

    public function __construct($table, $symbol)
    {
        $this->setSymbols($symbol);
        $this->table = $table;
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

    public function getPandoraEnemyMove()
    {
        $line = $this->getRowLine(1);
        $x = $this->findInLine($this->enemySymbol, $line);
        return [1, $x];
    }


    public function findInLine($symbol, $line)
    {
        return array_search($symbol, $line, true);
    }

    public function isCornerMove()
    {
        //todo finish
        return $this->countMoves() == 2;
    }

    public function isPandoraMovePattern($attacker, $defencer)
    {
        $linePattern = [null, $defencer, $attacker];
        Utils::log($linePattern);
        $row = $this->getRowLine(1);
        $column = $this->getColumnLine(1);

        return $this->hasPattern($row, $linePattern)
        && $this->hasPattern($column, $linePattern);
    }

    protected function setMySymbol($symbol)
    {
        $this->mySymbol = $symbol;
    }


    private function isOpponentMovesCrossEdge()
    {
        $pattern = [$this->mySymbol, $this->mySymbol, $this->enemySymbol];

        $type = $this->findCrossWithPattern($pattern);
        $line = $this->getCrossLine($type);
        $move = $this->findSymbolCoordsInCross($line, $type, $this->enemySymbol);

        return $this->isTurn($move) && $this->enemySymbolsNear($move);
    }

    public function isCrossEdgeMove()
    {
        return $this->countMoves() == 4 && $this->isOpponentMovesCrossEdge();
    }

    public function attackCornerMove()
    {
        $pattern = [null, $this->mySymbol, $this->enemySymbol];

        $type = $this->findCrossWithPattern($pattern);
        $line = $this->getCrossLine($type);
        return $this->findSymbolCoordsInCross($line, $type, null);
    }

    private function findCrossWithPattern($array)
    {
        return $this->findInCrosses('hasPattern', $array);
    }

    /**
     * Are enemy symbols one on corner, and one on edge next to
     *
     * @param $move
     *
     * @return mixed
     */
    private function enemySymbolsNear($move)
    {
        if ($this->opponnetMovedEdge()) {
            return $this->isCoordsNeighbours($this->edgeMove, $move);
        }

        return false;
    }

    public function areValuesNear($x, $y)
    {
        return ($x + $y) % 2 == 1;
    }

    public function hasPattern($line, $pattern)
    {
        return $line == $pattern || $line == array_reverse($pattern);
    }

    protected function getEnemyField($items)
    {
        return array_search($this->enemySymbol, $items);
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

    public function invertSymbols($inverted = null)
    {
        $this->setEnemySymbol($this->invertSymbol($this->enemySymbol));
        $this->setMySymbol($this->invertSymbol($this->mySymbol));
        if (isset($inverted)) {
            $this->inverted = $inverted;
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

        return null;
    }

    protected function isFullRow($rowIndex)
    {
        return $this->countRow($rowIndex) == 3;
    }

    protected function countRow($index, $type = 'any')
    {
        $line = $this->getRowLine($index);
        return $this->countBy($line, $type);
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

    protected function getTableHelper()
    {
        return new TableHelper($this->getTable());
    }

    /**
     * Shortcut method for count with filter
     *
     * @param $array
     * @param $type
     *
     * @return int
     */
    public function countBy($array, $type)
    {
        switch ($type) {
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

    protected function isFullColumn($rowIndex)
    {
        return $this->countColumn($rowIndex) == 3;
    }

    protected function countColumn($index, $type = 'any')
    {
        $line = $this->getColumnLine($index);
        return $this->countBy($line, $type);
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

    protected function getPossibleY($index)
    {
        $items = $this->getColumnLine($index);
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

    public function getInfo()
    {
        return "\nhero . $this->mySymbol, enemy $this->enemySymbol .\n"
        . json_encode($this->getTable());
    }

    protected function getPossibleX($index)
    {
        $items = $this->getRowLine($index);
        return $this->getEmptyField($items);
    }

    /**
     * @return array|null
     */
    protected function defendCross()
    {
        $crossType = $this->getDefendedCross();

        if (isset($crossType)) {
            $cross = $this->getCrossLine($crossType);

            return $this->findSymbolCoordsInCross($cross, $crossType, null);
        }

        return null;
    }

    protected function getDefendedCross()
    {
        return $this->findInCrosses('isDefendable');
    }

    /**
     * Return cross bool $rtl on success, null on fail
     * Callbacks will return booleans!
     * @param $callback
     * @return boolean|null Returns cross type on success, null on fail
     */
    protected function findInCrosses($callback)
    {
        foreach ([true, false] as $crossRtl) {
            $cross = $this->getCrossLine($crossRtl);

            if (method_exists($this, $callback)) {
                if (func_num_args() > 1) {
                    $args = func_get_args();
                    $args[0] = $cross;
                    return call_user_func_array(array($this, $callback), $args);
                }

                if ($this->$callback($cross)) {
                    return $crossRtl;
                }
            }
        }
        return null;
    }

    protected function getCrossLine($rtl)
    {

        $items = $this->getTableHelper()->getCross($rtl);
        return $items;
    }

    public function findSymbolCoordsInCross($line, $type, $symbol)
    {
        $x = array_search($symbol, $line);
        $y = $this->getCrossY($line, $type, $symbol);

        if (is_int($x) && is_int($y)) {
            return [$x, $y];
        }

        return null;
    }

    protected function getCrossY($cross, $crossRtl, $symbol = null)
    {
        if ($crossRtl === true) {
            $cross = array_reverse($cross);
        }

        return array_search($symbol, $cross);
    }

    protected function isDefendable($line)
    {
        $defendablePattern = [$this->he(), $this->he(), null];
        return $this->hasPattern($line, $defendablePattern);
    }

    protected function isAttackable($line)
    {
        $attackablePattern = [$this->me(), $this->me(), null];
        return $this->hasPattern($line, $attackablePattern);
    }

    public function countTableMovesBy($symbol)
    {
        $out = $this->findAllSymbolCoords($symbol);
        return count($out);
    }

    public function attackAttack()
    {
        $attack = array(
            [1, 1],
        );

        if ($this->countMoves() >= 1 || $this->countMoves() == 3) {
            $corners = $this->getCorners();
            //find weak spots
            shuffle($corners);
            $attack = array_merge($attack, $corners);
        }

        return $this->getPosssibleMove($attack, 'I attack!');
    }


    /**
     * Sorry for dirty OOP
     * @return bool
     */
    public function opponnetMovedEdge()
    {
        foreach ($this->getTable() as $row => $data) {
            foreach ($data as $col => $value) {
                if ($value === $this->enemySymbol && ($row == 1 || $col == 1)) {
                    $this->edgeMove = [$col, $row];
                    return true;
                }
            }
        }
        return false;
    }

    public function moveIsSecond()
    {
        return $this->countMoves() == 2;
    }

    public function countMoves()
    {
        return 9 - count($this->getPossibleMoves());
    }

    protected function getPossibleMoves()
    {
        return $this->getTableHelper()->getPossibleMoves();
    }

    public function attackEdgeMove()
    {
        list($x, $y) = $this->edgeMove;
        if ($x == 0 || $y == 0) {
            return [2, 2];
        }
        if ($x == 2 || $y == 2) {
            return [0, 0];
        }

        return null;
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
     * @param $moves
     * @param $action
     *
     * @return mixed
     */
    public function getPosssibleMove($moves, $action = false)
    {
        foreach ($moves as $move) {
            if ($this->isPossibleturn($move)) {
                if ($action) {
                    Utils::log($action);
                }
                return $move;
            }
        }
        return null;
    }

    public function isPossibleturn($move)
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
                return is_int($x);
            }
        );

        if (count($turn) == 2) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param $attack
     *
     * @return array
     */
    public function addCorners($attack)
    {
        $corners = $this->getCorners();
        //find weak spots
        shuffle($corners);
        $attack = array_merge($attack, $corners);
        return $attack;
    }

    /**
     * @return bool
     */
    public function isCornerAttackNeeded()
    {
        return $this->countMoves() == 1 || $this->countMoves() == 3;
    }

    public function randomMove()
    {
        $x = $y = 1;
        while ($this->getTable()[$x][$y] !== null) {
            $x = rand(0, 2);
            $y = rand(0, 2);
        }
        Utils::log(
            "I was dumb random!"
            . $this->getInfo()
        );
        return array($x, $y);
    }

    public function findEmptySpaceNear($targetMove)
    {

        $moves = $this->getAllNeigbourgCoords($targetMove);

        foreach ($moves as $move) {
            if ($this->isPossibleturn($move)) {
                Utils::log('Found empty space at' . var_export($move, true));
                return $move;
            }
        }

        return null;
    }

    public function getAllNeigbourgCoords($move)
    {

        list($x, $y) = $move;

        $moves[] = [abs($x - 1), $y];
        $moves[] = [$x, abs($y - 1)];

        if (!in_array($move, $this->getCorners())) {
            $moves[] = [abs($x + 1), $y];
            $moves[] = [$x, abs($y + 1)];

        }

        return $moves;
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

    /**
     * Sets symbols by my symbol
     * @param $symbol - My symbol
     */
    public function setSymbols($symbol)
    {
        $this->setMySymbol($symbol);
        $this->setEnemySymbol($this->invertSymbol($symbol));
    }

    public function me()
    {
        return $this->mySymbol;
    }

    public function he()
    {
        return $this->enemySymbol;
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
}
