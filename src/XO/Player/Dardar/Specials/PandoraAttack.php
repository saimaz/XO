<?php


namespace XO\Player\Dardar\Specials;


use system\M;
use XO\Player\Dardar\Move\Move;
use XO\Player\Dardar\Move\MoveNotFoundException;

class PandoraAttack extends AbstractSpecial implements SpecialsInterface
{
    /**
     * Check if move is possible in current situation
     * @return bool
     */
    public function isPossible()
    {
        return $this->isFirstNotSkipped()
        || ($this->situation->countMoves() == 2 && $this->situation->isPandoraAttackable()
        || ($this->situation->countMoves() == 4 && $this->situation->isPandoraMistake()));
    }

    /**
     * Get Move
     * @return Move
     */
    public function findMove()
    {
        switch ($this->situation->countMoves()) {
            case (0):
                return new Move(0, 1);
                break;
            case (2):
                return $this->situation->attackEdge();
                break;
        }

        return new Move(0, 0);

    }


}
