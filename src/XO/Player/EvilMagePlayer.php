<?php
namespace XO\Player;

//require_once("PlayerInterface.php");
//require_once("EvilBarbarianPlayer.php");

class EvilMagePlayer extends EvilBarbarianPlayer implements PlayerInterface
{
    /**
    * @param array $table
    * @param string $symbol
    * @returns array
    */

    public function turn($table, $symbol = self::SYMBOL_X)
    {
        $this -> symbol = $symbol;

        if ($cords = $this -> makeWinningMove($table)) {
            return $cords;
        }

        if ($cords = $this -> blockWinningMove($table)) {
            return $cords;
        }

        if ($cords = $this -> makeHalfWin($table)) {
            return $cords;
        }

        return $this -> makeRandomMove($table);
    }

    private function blockWinningMove($table)
    {
        $enemy_symbol = $this -> not($this -> symbol);

        if ($cords = $this -> makeHorizontalLine($table, $enemy_symbol, 1)) {
            return $cords;
        }

        if ($cords = $this -> makeVerticalLine($table, $enemy_symbol, 1)) {
            return $cords;
        }

        if ($cords = $this -> makeDiagonalLine($table, $enemy_symbol, 1)) {
            return $cords;
        }

        return false;
    }

    private function not($symbol)
    {
        if ($symbol === self::SYMBOL_X) {
            return self::SYMBOL_O;
        }

        return self::SYMBOL_X;
    }
}
