<?php


namespace XO\Player\Strategy;


class StrategySelector
{
    protected $situation;

    public function __construct(Situation $situation)
    {
        $this->situation = $situation;
    }

    public function getStrategy()
    {
        return new DefaultStrategy(
            new Actions(
                $this->situation->getTable(),
                $this->situation->getSymbol()
            )
        );
    }
}
