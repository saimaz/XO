<?php

namespace XO\Player;

/**
 * This class provides not very smart player ;)
 */
class DariusPlayer implements PlayerInterface
{

    /**
     * @inheritdoc
     */
    public function turn($table, $symbol = self::SYMBOL_O)
    {
        $x = $y = 1;

        if ($table[0][0] == null) {
            if (($table[1][1] == null || $table[1][1] == $symbol) && ($table[2][2] == null ||
                $table[2][2] == $symbol)) {
                return [0, 0];
            }
        }

        if ($table[0][0] == $symbol) {
            if ($table[1][1] == null && $table[2][2] == null) {
                return [1, 1];
            }
        }

        if ($table[0][0] == $symbol && $table[1][1] == $symbol && $table[2][2] == null) {
            return [2, 2];
        }

        if ($table[2][0] == null) {
            if (($table[1][1] == null || $table[1][1] == $symbol) && ($table[0][2] == null ||
                $table[0][2] == $symbol)) {
                return [2, 0];
            }
        }

        if ($table[0][0] == $symbol) {
            if ($table[1][1] == null && $table[2][2] == null) {
                return [1, 1];
            }
        }

        if ($table[0][2] == $symbol && $table[1][1] == $symbol && $table[0][2] == null) {
            return [0, 2];
        }

        for ($i = 0; $i < 3; $i++) {
            if ($table[$i][0] == null) {
                if (($table[$i][1] == null || $table[$i][1] == $symbol) && ($table[$i][2] == null ||
                    $table[$i][2] == $symbol)) {
                    return [$i, 0];
                }
            }

            if ($table[$i][0] == $symbol) {
                if ($table[$i][1] == null && $table[$i][2] == null) {
                    return [$i, 1];
                }
            }

            if ($table[$i][0] == $symbol && $table[$i][1] == $symbol && $table[$i][2] == null) {
                return [$i, 2];
            }
        }

        while ($table[$x][$y] !== null) {
            $x = rand(0, 2);
            $y = rand(0, 2);
        }

        return [$x, $y];

    }
}
