<?php

namespace XO\Player;

class RandomPlayer implements PlayerInterface
{
    /**
     * @inheritdoc
     */
    public function turn($table, $symbol = self::SYMBOL_X)
    {
        //Always start from middle
        $x = $y = 1;
        while ($table[$x][$y] !== null) {
            $x = rand(0,2);
            $y = rand(0,2);
        }

        return [$x,$y];
    }
} 