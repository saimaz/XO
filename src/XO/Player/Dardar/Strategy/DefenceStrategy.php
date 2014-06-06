<?php


namespace XO\Player\Dardar\Strategy;

use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\Specials\CenterMove;
use XO\Player\Dardar\Specials\CrossDefence;
use XO\Player\Dardar\Utils;

class DefenceStrategy extends AbstractStrategy
{
    public function attack()
    {
        $specialMoves = new SpecialMoveFinder($this->situation);
        $specialMoves->add(new CenterMove());
        $specialMoves->add(new CrossDefence());

        try {
            $this->addAttack($specialMoves->getMove());

        } catch (MoveNotFoundException $e) {

        }

        if ($this->isOponentPandoraMove()) {
            $this->addAttack($this->getPandoraDefenceMove());
        } elseif ($this->situation->isCornerAttackNeeded()) {
            $corners = $this->situation->addShuffledCorners();
            $this->addAttacks($corners);
        }

        return $this->situation->getPosssibleMove($this->getAttack(), 'I attack!');
    }


    public function isOponentPandoraMove()
    {
        return $this->situation->isPandoraMove($this->he(), $this->me());
    }

    public function getPandoraDefenceMove()
    {
        $move = $this->situation->getPandoraEnemyMove();
        Utils::log('Pandora defence - enemy on: ' . var_export($move, true));
        return $this->situation->findEmptySpaceNear($move);
    }

}
