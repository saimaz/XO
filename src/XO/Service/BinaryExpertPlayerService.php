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

    /**
     * Add move to the cell and checks if new state has win
     *
     * @param $state
     * @param $cellNum
     * @param $nextTurn
     * @return int
     */
    public function detectWinMove($state, $cellNum, $nextTurn)
    {
        $value = 0b11;
        if ($nextTurn == -1) {
            $value = 0b10;
        }
        $newState = $state | ($value << $cellNum*2);
        return $this->detectWin($newState);
    }

    /**
     * Check if this state is already won
     *
     * @param $state
     * @return int
     */
    public function detectWin($state)
    {
        if (($state & 0b111111000000000000) == 0b111111000000000000) {
            return 0b0100111111000000000000;
        }
        if (($state & 0b111111000000000000) == 0b101010000000000000) {
            return 0b1000101010000000000000;
        }
        if (($state & 0b000000111111000000) == 0b000000111111000000) {
            return 0b0100000000111111000000;
        }
        if (($state & 0b000000111111000000) == 0b000000101010000000) {
            return 0b1000000000101010000000;
        }
        if (($state & 0b000000000000111111) == 0b000000000000111111) {
            return 0b0100000000000000111111;
        }
        if (($state & 0b000000000000111111) == 0b000000000000101010) {
            return 0b1000000000000000101010;
        }
        if (($state & 0b000011000011000011) == 0b000011000011000011) {
            return 0b0100000011000011000011;
        }
        if (($state & 0b000011000011000011) == 0b000010000010000010) {
            return 0b1000000010000010000010;
        }
        if (($state & 0b001100001100001100) == 0b001100001100001100) {
            return 0b0100001100001100001100;
        }
        if (($state & 0b001100001100001100) == 0b001000001000001000) {
            return 0b1000001000001000001000;
        }
        if (($state & 0b110000110000110000) == 0b110000110000110000) {
            return 0b0100110000110000110000;
        }
        if (($state & 0b110000110000110000) == 0b100000100000100000) {
            return 0b1000100000100000100000;
        }
        if (($state & 0b000011001100110000) == 0b000011001100110000) {
            return 0b0100000011001100110000;
        }
        if (($state & 0b000011001100110000) == 0b000010001000100000) {
            return 0b1000000010001000100000;
        }
        if (($state & 0b110000001100000011) == 0b110000001100000011) {
            return 0b0100110000001100000011;
        }
        if (($state & 0b110000001100000011) == 0b100000001000000010) {
            return 0b1000100000001000000010;
        }
        if (($state & 0b101010101010101010) == 0b101010101010101010) {
            return 0b1100000000000000000000;
        }
        return 0;
    }

    public function openingBook($state)
    {
        $mask = $state & 0b101010101010101010;
//        if ($mask == 0x000000000000000000) return 0b111111111;      // empty table, go to any cell
        if ($mask == 0x000000000000000000) return 0b101010101;      // empty table, go to any cell except middle border
        if ($mask == 0b000000001000000000) return 0b101000101;      // used center, go to corner
        if ($mask == 0b000000000000000010 ||
            $mask == 0b000000000000100000 ||
            $mask == 0b000010000000000000 ||
            $mask == 0b100000000000000000) return 0b000010000;      // any corner is used, go to center
        if ($mask == 0b000000000000001000) return 0b010010101;      // top middle used, go to good places
        if ($mask == 0b000000000010000000) return 0b001110001;      // left middle used, go to good places
        if ($mask == 0b000000100000000000) return 0b100011100;      // right middle used, go to good places
        if ($mask == 0b001000000000000000) return 0b101010010;      // bottom middle used, go to good places
        return 0;
    }

    public function getPerfectMove($state, $turn)
    {
        $moves = $this->getLegalMoves($state);
        $hope = -999;
        $goodMoves = $this->openingBook($state);
        if ($goodMoves == 0) {
            for ($i=0; $i<9; $i++) {
                if (($moves & (1<<$i)) != 0) {
                    $value = $this->moveValue($state, $i, $turn, $turn, 15, 1);
                    if ($value > $hope) {
                        $hope = $value;
                        $goodMoves = 0;
                    }
                    if ($hope == $value) {
                        $goodMoves |= (1 << $i);
                    }
                }
            }
        }
        return $this->getRandomMove($goodMoves);
    }

    public function moveValue($istate, $move, $moveFor, $nextTurn, $limit, $depth)
    {
        $state = $this->stateMove($istate, $move, $nextTurn);
        $winner = $this->detectWin($state);
        if (($winner & 0x300000) == 0x300000) {
            return 0;
        } else {
            if ($winner != 0) {
                if ($moveFor == $nextTurn) {
                    return 10 - $depth;
                } else {
                    return $depth - 10;
                }
            }
        }
        $hope = 999;
        if ($moveFor != $nextTurn) {
            $hope = -999;
        }
        if ($depth == $limit) {
            return $hope;
        }
        $moves = $this->getLegalMoves($state);
        for ($i = 0; $i < 9; $i++) {
            if (($moves & (1 << $i)) != 0) {
                $value = $this->moveValue($state, $i, $moveFor, -$nextTurn, 10 - abs($hope), $depth + 1);
                if (abs($value) != 999) {
                    if ($moveFor == $nextTurn && $value < $hope) {
                        $hope = $value;
                    } else {
                        if ($moveFor != $nextTurn && $value > $hope) {
                            $hope = $value;
                        }
                    }
                }
            }
        }
        return $hope;
    }

    public function stateMove($state, $move, $nextTurn)
    {
        $value = 0x3;
        if ($nextTurn == -1) {
            $value = 0x2;
        }
        return ($state | ($value << ($move*2)));
    }
}
