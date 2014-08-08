<?php

namespace XO\Player\Dardar\Specials;

use XO\Player\Dardar\Move\Move;

interface SpecialsInterface
{
    const MOVE_FIRST = 'move_first';

    const MOVE_SECOND = 'move_second';

    /**
     * Check if move is possible in current situation
     * @return bool
     */
    public function isPossible();

    /**
     * Get Move
     * @return Move
     */
    public function findMove();
}
