<?php

namespace XO\Player\Dardar\Specials;

use XO\Player\Dardar\Move\Move;
use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\Situation;

class EdgeAttack extends AbstractSpecial implements SpecialsInterface
{

    protected $edgeMove;

    public function isPossible()
    {
        return $this->hasOpponnetMovedEdge() && $this->situation->moveIsSecond();
    }

    public function findMove()
    {
        list($x, $y) = $this->edgeMove;
        if ($x == 0 || $y == 0) {
            return new Move(2, 2);
        }
        if ($x == 2 || $y == 2) {
            return new Move(0, 0);
        }

        throw new MoveNotFoundException();
    }

    /**
     * Sorry for dirty OOP
     * @return bool
     */
    public function hasOpponnetMovedEdge()
    {
        foreach ($this->situation->getTable() as $row => $data) {
            foreach ($data as $col => $value) {
                if ($value === $this->situation->he() && ($row == 1 || $col == 1)) {
                    $this->edgeMove = [$col, $row];
                    return true;
                }
            }
        }
        return false;
    }
}

