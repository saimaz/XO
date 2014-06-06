<?php


namespace XO\Player\Dardar\Specials;


use XO\Player\Dardar\Move\Move;

class CrossDefence extends AbstractSpecial implements SpecialsInterface
{
    /**
     * Check if move is possible in current situation
     * @return bool
     */
    public function isPossible()
    {
        return $this->situation->countMoves() == 3 && $this->situation->isCrossAttack();
    }

    /**
     * Get Move
     * @return Move
     */
    public function findMove()
    {
        return new Move(0, 1);
    }
}
