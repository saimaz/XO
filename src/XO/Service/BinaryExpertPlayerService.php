<?php


namespace XO\Service;


class BinaryExpertPlayerService
{
    /**
     * Cells array (9 elementai), kuriuose yra 'X' 'O' arba null
     * sias reiksmes sudeda i viena skaiciu dvejetainiame pavidale.
     * Sis dvejetainis skaicius ir atitinka dabartine busena (state)
     *
     * @param $table
     * @param $xSymbol
     * @param $oSymbol
     * @return int
     */
    public function getState($table, $xSymbol, $oSymbol)
    {
        $cells = [
            $table[0][0],
            $table[0][1],
            $table[0][2],
            $table[1][0],
            $table[1][1],
            $table[1][2],
            $table[2][0],
            $table[2][1],
            $table[2][2],
        ];

        $state = 0;

        for ($i=0; $i<9; $i++) {
            $cell = $cells[$i];
            $value = 0;

            if ($cell === $xSymbol) {
                $value = 0b11;
            }

            if ($cell === $oSymbol) {
                $value = 0b10;
            }

            $state |= $value << ($i*2);
        }

        return $state;
    }

    public function getLegalMoves($state)
    {
        $moves = 0;
        for ($i = 0; $i < 9; $i++) {
            if (($state & (1 << ($i * 2 + 1))) == 0) {
                $moves |= 1 << $i;
            }
        }
        return $moves;
    }

    public function getRandomMove($legalMoves)
    {
        $numMoves = 0;
        for ($i = 0; $i < 9; $i++) {
            if (($legalMoves & (1 << $i)) != 0) {
                $numMoves++;
            }
        }
        if ($numMoves > 0) {
            $moveNum = rand(1, $numMoves);
            $numMoves = 0;
            for ($j = 0; $j < 9; $j++) {
                if (($legalMoves & (1 << $j)) != 0) {
                    $numMoves++;
                }
                if ($numMoves == $moveNum) {
                    return $j;
                }
            }
        }
    }

    public function getMoveXY($moveIndex)
    {
        if (0 === $moveIndex) {
            return [0, 0];
        }
        if (1 === $moveIndex) {
            return [0, 1];
        }
        if (2 === $moveIndex) {
            return [0, 2];
        }
        if (3 === $moveIndex) {
            return [1, 0];
        }
        if (4 === $moveIndex) {
            return [1, 1];
        }
        if (5 === $moveIndex) {
            return [1, 2];
        }
        if (6 === $moveIndex) {
            return [2, 0];
        }
        if (7 === $moveIndex) {
            return [2, 1];
        }
        if (8 === $moveIndex) {
            return [2, 2];
        }
    }
}
