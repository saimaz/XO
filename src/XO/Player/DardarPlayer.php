<?php

namespace XO\Player;

use XO\Player\Dardar\Situation;
use XO\Player\Dardar\StrategySelector;
use XO\Player\Dardar\Utils;

/**
 * This class provides not very smart player ;)
 */
class DardarPlayer implements PlayerInterface
{

    protected $symbol;

    /**
     * @inheritdoc
     */
    public function turn($table, $symbol = self::SYMBOL_X)
    {


        $strategySelector = new StrategySelector(
            new Situation($table, $symbol)
        );
        $strategy = $strategySelector->getStrategy();

        list($x, $y) = $strategy->getTurn();
        Utils::log("I will move $x, $y");
        return [$x, $y];
    }
}
