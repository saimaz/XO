<?php

namespace XO\Player;

/**
 * This class ...
 */
class AlfPlayer implements PlayerInterface
{
    /**
     * @inheritdoc
     */
    public function turn($table, $symbol = self::SYMBOL_X)
    {
        return $this->findBestMove($table, $symbol);
    }

    public function findBestMove($table, $symbol)
    {
        // Try to win

        if ($turn = $this->winRow($table, $symbol)) {
            return $turn;
        }

        if ($turn = $this->winCol($table, $symbol)) {
            return $turn;
        }

        if ($turn = $this->winDiag($table, $symbol)) {
            return $turn;
        }


        // Try to defend
        $oponent = $symbol === self::SYMBOL_O?self::SYMBOL_X:self::SYMBOL_O;

        if ($turn = $this->winRow($table, $oponent)) {
            return $turn;
        }

        if ($turn = $this->winCol($table, $oponent)) {
            return $turn;
        }

        if ($turn = $this->winDiag($table, $oponent)) {
            return $turn;
        }

        //Always start from middle
        $x = $y = 1;
        while ($table[$x][$y] !== null) {
            $x = rand(0, 2);
            $y = rand(0, 2);
        }

        return [$x, $y];
    }

    public function winRow($table, $symbol)
    {
        for ($row = 0; $row<3; $row++) {
            if ($table[0][$row] === null && $table[1][$row] === $symbol && $table[2][$row] === $symbol) {
                return [0,$row];
            }
            if ($table[0][$row] === $symbol && $table[1][$row] === null && $table[2][$row] === $symbol) {
                return [1,$row];
            }
            if ($table[0][$row] === $symbol && $table[1][$row] === $symbol && $table[2][$row] === null) {
                return [2,$row];
            }
        }
    }

    public function winCol($table, $symbol)
    {
        for ($col = 0; $col<3; $col++) {
            if ($table[$col][0] === null && $table[$col][1] === $symbol && $table[$col][2] === $symbol) {
                return [$col, 0];
            }
            if ($table[$col][0] === $symbol && $table[$col][1] === null && $table[$col][2] === $symbol) {
                return [$col, 1];
            }
            if ($table[$col][0] === $symbol && $table[$col][1] === $symbol && $table[$col][2] === null) {
                return [$col, 2];
            }
        }
    }

    public function winDiag($table, $symbol)
    {
        if ($table[0][0] === null && $table[1][1] === $symbol && $table[2][2] === $symbol) {
            return [0, 0];
        }
        if ($table[0][0] === $symbol && $table[1][1] === null && $table[2][2] === $symbol) {
            return [1, 1];
        }
        if ($table[0][0] === $symbol && $table[1][1] === $symbol && $table[2][2] === null) {
            return [2, 2];
        }

        if ($table[0][2] === null && $table[1][1] === $symbol && $table[2][0] === $symbol) {
            return [0, 2];
        }
        if ($table[0][2] === $symbol && $table[1][1] === null && $table[2][0] === $symbol) {
            return [1, 1];
        }
        if ($table[0][2] === $symbol && $table[1][1] === $symbol && $table[2][0] === null) {
            return [2, 0];
        }
    }
}
