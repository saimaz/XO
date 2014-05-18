<?php

namespace XO\Player;

use XO\Utilities\ChromePhp;
use XO\Player\Strategy\Situation;
use XO\Player\Strategy\StrategySelector;
use XO\Utilities\TableHelper;

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
        //ChromePhp::log($table);

        $strategySelector = new StrategySelector(
            new Situation($table, $symbol)
        );
        $strategy = $strategySelector->getStrategy();

        list($x, $y) = $strategy->getTurn();
        while ($table[$x][$y] !== null) {
            $turn = $strategy->getTurn();
            list($x, $y) = $turn;
        }
        //Always start from middle
        //        $x = $y = 1;
        //        while ($table[$x][$y] !== null) {
        //            list($x, $y) = $this->getCoordinates($table);
        //        }
        return [$x, $y];
    }
}
