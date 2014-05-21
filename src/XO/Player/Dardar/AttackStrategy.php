<?php


namespace XO\Player\Dardar;



class AttackStrategy extends AbstractStrategy
{
    public function attack()
    {
        $attack = array(
            [1, 1],
        );

        if ($this->isEdgeMove()) {
            $attack[] = $this->situation->attackEdgeMove();
        } elseif ($this->situation->isCornerMove()) {
            $attack[] = $this->situation->attackCornerMove();
        } elseif ($this->situation->isCornerAttackNeeded()) {
            $attack = $this->situation->addCorners($attack);

        }

        Utils::log('Attack strategy (attack)' . var_export($attack, true));

        return $this->situation->getPosssibleMove($attack, 'I attack!');
    }


    public function isEdgeMove()
    {
        return $this->situation->opponnetMovedEdge() && $this->situation->moveIsSecond();
    }

}
