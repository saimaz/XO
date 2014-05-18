<?php

namespace XO\Player;

use XO\Player\Strategy\GameActions;
use XO\Player\Strategy\StrategySelector;

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
            new GameActions($table, $symbol)
        );
        $strategy = $strategySelector->getStrategy();

        list($x, $y) = $strategy->getTurn();
        return [$x, $y];
    }
}
