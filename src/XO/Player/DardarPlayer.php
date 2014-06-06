<?php

namespace XO\Player;

use XO\Player\Dardar\Situation;
use XO\Player\Dardar\Strategy\StrategySelector;
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

        list($x, $y) = $strategySelector->getStrategy()->getTurn();
        Utils::log("I will move $x, $y");
        return [$x, $y];
    }
}
