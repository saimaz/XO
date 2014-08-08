<?php


namespace XO\Player\Dardar\Strategy;

use XO\Player\Dardar\Move\Move;
use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\Specials\CenterMove;
use XO\Player\Dardar\Specials\CornerAttack;
use XO\Player\Dardar\Specials\CrossAttack;
use XO\Player\Dardar\Specials\EdgeAttack;
use XO\Player\Dardar\Specials\PandoraAttack;
use XO\Player\Dardar\Utils;

class AttackStrategy extends AbstractStrategy
{


    public function attack()
    {
        $specialMoves = new SpecialMoveFinder($this->situation);

        $specialMoves->add(new PandoraAttack($this->situation), 80);
        $specialMoves->add(new CrossAttack($this->situation), 80);
        $specialMoves->add(new CenterMove($this->situation));
        $specialMoves->add(new EdgeAttack($this->situation));
        $specialMoves->add(new CornerAttack($this->situation));

        try {
            $this->addAttack($specialMoves->getMove());

        } catch (MoveNotFoundException $e) {

        }

        if ($this->situation->isCornerAttackNeeded()) {
            $corners = $this->situation->addShuffledCorners();
            $this->addAttacks($corners);
        }

        Utils::log('Attack strategy (attack)' . var_export($this->getAttack(), true));

        return $this->situation->getPosssibleMove($this->getAttack(), 'I attack!');
    }
}
