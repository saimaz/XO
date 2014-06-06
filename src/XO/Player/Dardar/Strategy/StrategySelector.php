<?php


namespace XO\Player\Dardar\Strategy;


use XO\Player\Dardar\Situation;
use XO\Player\PlayerInterface;

class StrategySelector
{
    protected $situation;

    public function __construct(Situation $situation)
    {
        $this->situation = $situation;
    }

    /**
     * Let's start the AI magic
     * Pick the best strategy by anallizing enemy source code
     * and get his next move
     *
     * @return aa
     */
    public function getStrategy()
    {
        //AI stuff will be added
        //$ai = new AI();
        if ($this->iAmAttacker()) {
            $this->situation->setSymbols(PlayerInterface::SYMBOL_X);
            return new AttackStrategy($this->situation);
        } else {
            $this->situation->setSymbols(PlayerInterface::SYMBOL_O);
            return new DefenceStrategy($this->situation);
        }
    }

    protected function iAmAttacker()
    {
        return $this->situation->countMoves() % 2 == 0;
    }
}
