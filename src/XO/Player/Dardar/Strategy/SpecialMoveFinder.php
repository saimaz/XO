<?php


namespace XO\Player\Dardar\Strategy;


use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\Move\NullMove;
use XO\Player\Dardar\Situation;
use XO\Player\Dardar\Specials\AbstractSpecial;
use XO\Player\Dardar\Specials\SpecialsInterface;

class SpecialMoveFinder
{
    protected $specials = array();

    protected $situation;

    public function __construct(Situation $situation)
    {
        $this->situation = $situation;
    }

    /**
     * @param AbstractSpecial $attack
     * @param $skippPercent int how much times in percent we will skip it?
     */
    public function add(AbstractSpecial $attack, $skippPercent = 0)
    {
        $this->specials[] = $attack
            ->setSituation($this->situation)
            ->setSkipped($skippPercent);
    }

    public function getMove()
    {
        foreach ($this->specials as $specialMove) {
            try {

                /** @var SpecialsInterface $specialMove  */
                if ($specialMove->isPossible()) {
                    return $specialMove->findMove();
                }

            } catch (MoveNotFoundException $e) {
                continue;
            }
        }

        throw new MoveNotFoundException('Special attack not found');
    }

}
