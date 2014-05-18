<?php


namespace XO\Player\Strategy;


class StrategySelector
{
    protected $situation;

    public function __construct(GameActions $situation)
    {
        $this->situation = $situation;
    }

    public function getStrategy()
    {
        return new DefaultStrategy($this->situation);
    }
}
