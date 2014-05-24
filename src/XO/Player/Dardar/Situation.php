<?php


namespace XO\Player\Dardar;

use XO\Player\PlayerInterface;
use XO\Utilities\TableHelper;

/**
 * @property mixed edgeMove
 */
class Situation extends SituationCounter
{

    public function isPandoraMove($attacker, $defencer)
    {
        $moves = $this->countMoves() == 3;
        return $moves && $this->isPandoraMovePattern($attacker, $defencer);
    }

    public function getKillDefendCoordinates()
    {
        $action = $this->inverted ? 'Killing on' : 'Defending on';

        $x = $this->defendLine('column');
        if (null !== $x) {
            $y = $this->getPossibleY($x);
            Utils::log("$action  col in $x, $y " . $this->getInfo());
            return array($y, $x);
        }

        $y = $this->defendLine('row');
        if (null !== $y) {
            $x = $this->getPossibleX($y);
            Utils::log("$action row in $x, $y" . $this->getInfo());
            return [$y, $x];
        }

        $move = $this->defendCross();
        if ($this->isTurn($move)) {
            list($x, $y) = $move;
            Utils::log("$action cross in $x, $y" . $this->getInfo());
            return $move;
        }
    }
}
