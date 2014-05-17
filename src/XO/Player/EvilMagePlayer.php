<?php
namespace XO\Player;

require_once("PlayerInterface.php");
require_once("EvilBarbarianPlayer.php");

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

        if( $cords = $this -> make_winning_move($table) )
            return $cords;

        if( $cords = $this -> block_winning_move($table) )
            return $cords;

        if( $cords = $this -> make_second_line_item($table) )
            return $cords;

        return $this -> make_random_move($table);
    }

    private function block_winning_move($table)
    {
        $enemy_symbol = $this -> not($this -> symbol);

        if( $cords = $this -> make_horizontal_line($table, $enemy_symbol, 1) )
            return $cords;

        if( $cords = $this -> make_vertical_line($table, $enemy_symbol, 1) )
            return $cords;

        if( $cords = $this -> make_diagonal_line($table, $enemy_symbol, 1) )
            return $cords;

        return false;
    }

    private function not( $symbol )
    {
        if( $symbol === self::SYMBOL_X )
            return self::SYMBOL_O;
        return self::SYMBOL_X;
    }
}

