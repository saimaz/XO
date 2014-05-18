<?php

namespace XO\Player;

class DarxxPlayer implements PlayerInterface
{
    private $table = array();
    private $symbol;

    private function checker()
    {
        if ($this->table[1][1] != $this->symbol) {
            return [1, 1];
        } else {
            if (
                $this->table[1][0] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[1][2])
            ) {
                return [
                    1,
                    2
                ];
            }
            if (
                $this->table[1][2] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[1][0])
            ) {
                return [
                    1,
                    0
                ];
            }

            if (
                $this->table[0][1] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[2][1])
            ) {
                return [
                    2,
                    1
                ];
            }
            if (
                $this->table[2][1] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[0][1])
            ) {
                return [
                    0,
                    1
                ];
            }

            if ($this->table[0][0] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[2][2])) {
                return [
                    2,
                    2
                ];
            }
            if ($this->table[2][2] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[0][0])) {
                return [
                    0,
                    0
                ];
            }

            if ($this->table[0][2] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[2][0])) {
                return [
                    2,
                    0
                ];
            }
            if ($this->table[2][0] == $this->symbol && $this->table[1][1] == $this->symbol
                && empty($this->table[0][2])) {
                return [
                    0,
                    2
                ];
            }

            if (empty($this->table[0][0])) {
                return [0, 0];
            }
            if (empty($this->table[0][2])) {
                return [0, 2];
            }
            if (empty($this->table[0][1])) {
                return [0, 1];
            }

            if (empty($this->table[0][0])) {
                return [0, 0];
            }
            if (empty($this->table[1][0])) {
                return [1, 0];
            }
            if (empty($this->table[2][0])) {
                return [2, 0];
            }

            if (empty($this->table[2][0])) {
                return [2, 0];
            }
            if (empty($this->table[2][1])) {
                return [2, 1];
            }
            if (empty($this->table[2][2])) {
                return [2, 2];
            }
        }
        return false;
    }

    public function turn($table, $symbol = self::SYMBOL_X)
    {
        $this->table = $table;
        $this->symbol = $symbol;
        $move = $this->checker();
        return $move;
    }
}
