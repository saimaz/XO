<?php


namespace XO\Player\Strategy;


class StrategySelector
{
    protected $situation;

    public function __construct(GameActions $situation)
    {
        $this->situation = $situation;
    }

    /**
     * Let's start the AI magic
     * Pick the best strategy by anallizing enemy source code
     * and get his next move
     *
     * @return DefaultStrategy
     */
    public function getStrategy()
    {
        //AI stuff will be added
        //$ai = new AI();
        return new DefaultStrategy($this->situation);
    }
}
