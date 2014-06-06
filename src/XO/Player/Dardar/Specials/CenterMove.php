<?php

namespace XO\Player\Dardar\Specials;

use XO\Player\Dardar\Move\Move;

class CenterMove extends AbstractSpecial implements SpecialsInterface
{
    public function isPossible()
    {
        return $this->situation->countMoves() == 0 || $this->situation->countMoves() == 1;
    }

    public function findMove()
    {
        return new Move(1, 1);
    }
}
