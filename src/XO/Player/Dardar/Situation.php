<?php

namespace XO\Player\Dardar;

use Guzzle\Tests\Common\Cache\NullCacheAdapterTest;
use XO\Player\Dardar\Move\Move;
use XO\Player\Dardar\Move\MoveException;
use XO\Player\Dardar\Move\MoveInterface;
use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\Move\NullMove;
use XO\Player\PlayerInterface;
use XO\Utilities\TableHelper;

/**
 * @property mixed edgeMove
 */
class Situation extends SituationCounter
{

    public function getPandoraEnemyMove()
    {
        $line = $this->getRowLine(1);
        $x = $this->findInLine($this->enemySymbol, $line);
        return new Move(1, $x);
    }

    public function isCrossAttack()
    {
        return is_bool($this->findInCrosses('isCrossAttackPattern'));
    }

    /**
     * @param $attack
     *
     * @return array
     */
    public function addCorners(array $attack)
    {
        $corners = $this->addShuffledCorners();
        return  array_merge($attack, $corners);
    }

    public function addShuffledCorners()
    {
        $corners = $this->getCornerMoves();
        //find weak spots
        shuffle($corners);
        return $corners;
    }

    protected function getCornerMoves()
    {
        return $this->createMovesFromCoords($this->getCorners());
    }

    public function getAllNeigbourgCoords(Move $move)
    {

        list($x, $y) = $move->getMove();

        $moves[] = [abs($x - 1), $y];
        $moves[] = [$x, abs($y - 1)];

        if (!in_array($move, $this->getCorners())) {
            $moves[] = [abs($x + 1), $y];
            $moves[] = [$x, abs($y + 1)];

        }

        return $moves;
    }

    public function findEmptySpaceNear(Move $targetMove)
    {

        $coordanates = $this->getAllNeigbourgCoords($targetMove);

        $moves = $this->createMovesFromCoords($coordanates);

        foreach ($moves as $move) {
            if ($this->isPossibleTurn($move)) {
                Utils::log('Found empty space at' . var_export($move, true));
                return $move;
            }
        }

        return new NullMove();
    }

    public function moveIsSecond()
    {
        return $this->countMoves() == 2;
    }


    public function findCrossWithPattern($array)
    {
        return $this->findInCrosses('hasPattern', $array);
    }

    /**
     * @return bool
     */
    public function isCornerAttackNeeded()
    {
        return $this->countMoves() == 1 || $this->countMoves() == 3;
    }

    /**
     * @param $attacker
     * @param $defencer
     *
     * @return bool
     */
    public function isPandoraMove($attacker, $defencer)
    {
        $moves = $this->countMoves() == 3;
        return $moves && $this->isPandoraMovePattern($attacker, $defencer);
    }

    /**
     * @param $attacker
     * @param $defender
     *
     * @return bool
     */
    public function isPandoraMovePattern($attacker, $defender)
    {
        $linePattern = [null, $defender, $attacker];
        Utils::log($linePattern);
        $row = $this->getRowLine(1);
        $column = $this->getColumnLine(1);

        return $this->hasPattern($row, $linePattern)
        && $this->hasPattern($column, $linePattern);
    }

    /**
     * @return bool
     */
    public function isPandoraAttackable()
    {
        $linePattern = [null, $this->he(), $this->me()];
        Utils::log($linePattern);
        $row = $this->getRowLine(1);
        $column = $this->getColumnLine(1);

        return $this->hasPattern($row, $linePattern) || $this->hasPattern($column, $linePattern);
    }

    public function isPandoraMistake()
    {
        $pattern1 = [$this->me(), $this->he(), $this->he()];
        $pattern2 = [null, $this->he(), $this->me()];
        $row = $this->getRowLine(1);
        $column = $this->getColumnLine(1);

        return ($this->hasPattern($row, $pattern1) && $this->hasPattern($column, $pattern2)) ||
            ($this->hasPattern($column, $pattern1) && $this->hasPattern($row, $pattern2));
    }

    public function attackEdge()
    {
        $pattern = [null, $this->he(), null];
        $row = $this->getRowLine(1);
        $column = $this->getColumnLine(1);

        if ($this->hasPattern($row, $pattern)) {
            $x = $this->findInLine(null, $row);
            return new Move(0, 1);
        }

        if ($this->hasPattern($column, $pattern)) {
            $y = $this->findInLine(null, $column);
            return new Move(1, 0);
        }

    }

    public function getDefendOrKillMove()
    {
        try {
            $move = $this->findDefendRowMove();
            return $move;

        } catch (MoveNotFoundException $e) {

        }

        try {
            $move = $this->findDefendColumnMove();
            return $move;

        } catch (MoveNotFoundException $e) {

        }

        try {
            $move = $this->findDefendCrossMove();
            return $move;

        } catch (MoveNotFoundException $e) {

        }

        return new NullMove();
    }

    public function isMove($move)
    {
        if ($move instanceof NullMove) {
            throw new MoveNotFoundException('This move is not possible');
        }
        return true;
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
     * @throws MoveNotFoundException
     * @return Move
     */
    protected function findDefendColumnMove()
    {
        foreach ([0, 1, 2] as $index) {
            $line = $this->getColumnLine($index);
            if ($this->isDefendable($line)) {
                $y = $this->findInLine(null, $line);
                return new Move($index, $y);
            }
        }

        throw new MoveNotFoundException();
    }

    /**
     * @throws MoveNotFoundException
     * @return Move
     */
    protected function findDefendRowMove()
    {
        foreach ([0, 1, 2] as $index) {
            $line = $this->getRowLine($index);
            if ($this->isDefendable($line)) {
                $x = $this->findInLine(null, $line);
                return new Move($x, $index);
            }
        }

        throw new MoveNotFoundException();
    }

    /**
     * @return Move
     * @throws MoveNotFoundException
     */
    public function findDefendCrossMove()
    {
        $crossType = $this->findDefendedCross();

        if (isset($crossType)) {
            $cross = $this->getCrossLine($crossType);

            return $this->createMove($this->getSymbolCoordsInCross($cross, $crossType, null));
        }

        throw new MoveNotFoundException();
    }

    /**
     * Create move from array
     *
     * @param array $array
     * @return Move
     */
    public function createMove(array $array)
    {
        list($x, $y) = $array;
        return new Move($x, $y);
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
            if ($this->isPossibleTurn($move)) {
                if ($action) {
                    Utils::log($action);
                }
                return $move;
            }
        }
        return new NullMove();
    }

    /**
     * @param $move
     *
     * @return bool
     */
    public function isPossibleTurn(MoveInterface $move)
    {
        try {
            return $this->isCoordinatedEmpty($move->getMove());

        } catch (MoveNotFoundException $e) {
            return false;
        }
    }


    public function createMovesFromCoords(array $array)
    {
        $movesArray = [];
        foreach ($array as $coordinates) {
            $movesArray[] = $this->createMove($coordinates);
        }
        return $movesArray;
    }



}

