<?php


namespace XO\Player\Dardar\Specials;


class CornerAttack extends AbstractSpecial implements SpecialsInterface
{
    public function isPossible()
    {
        return $this->situation->countMoves() == 2;
    }

    public function findMove()
    {
        $type = $this->situation->findCornerAttackableCross();
        $line = $this->situation->getCrossLine($type);
        return $this->situation->createMove(
            $this->situation->findSymbolCoordsInCross($line, $type, null)
        );
    }
}
