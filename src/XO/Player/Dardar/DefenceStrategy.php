<?php


namespace XO\Player\Dardar;

class DefenceStrategy extends AbstractStrategy
{
    public function attack()
    {
        $attack = array(
            [1, 1],
        );

        if ($this->isOponentPandoraMove()) {
            $attack[] = $this->getPandoraDefenceMove();
        } elseif ($this->situation->isCornerAttackNeeded()) {
            $attack = $this->situation->addCorners($attack);
        }

        return $this->situation->getPosssibleMove($attack, 'I attack!');
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
