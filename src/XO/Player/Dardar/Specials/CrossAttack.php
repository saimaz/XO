<?php


namespace XO\Player\Dardar\Specials;


use XO\Player\Dardar\Move\Move;

class CrossAttack extends AbstractSpecial implements SpecialsInterface
{
    /**
     * Check if move is possible in current situation
     * @return bool
     */
    public function isPossible()
    {
        return $this->isFirstNotSkipped() || $this->situation->countMoves() == 2;
    }

    /**
     * Get Move
     * @return Move
     */
    public function findMove()
    {
        if ($this->situation->countMoves() == 0) {
            return new Move(0, 0);
        }

        return $this->moveSecondCrossAttack();
    }

    public function moveSecondCrossAttack()
    {
        $rtl = $this->situation->findCrosAttackableCross();
        $line = $this->situation->getCrossLine($rtl);
        return $this->situation->createMove(
            $this->situation->findSymbolCoordsInCross($line, $rtl, null)
        );
    }

}
