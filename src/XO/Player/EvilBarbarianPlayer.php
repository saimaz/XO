<?php
namespace XO\Player;

require_once("PlayerInterface.php");

/**
* Agressive player with no defence
*/

class EvilBarbarianPlayer implements PlayerInterface
{
    protected $symbol = null;
    const TABLE_WIDTH = 3;
    const TABLE_HEIGHT = 3;

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

        if( $cords = $this -> make_second_line_item($table) )
            return $cords;

        return $this -> make_random_move($table);
    }

    /**
    * @param array $table
    * @return array|bool
    */

    protected function make_winning_move($table)
    {
        if( $cords = $this -> make_horizontal_line($table, $this -> symbol, 1) )
            return $cords;

        if( $cords = $this -> make_vertical_line($table, $this -> symbol, 1) )
            return $cords;

        if( $cords = $this -> make_diagonal_line($table, $this -> symbol, 1) )
            return $cords;

        return false;
    }

    protected function make_horizontal_line($table, $symbol, $spaces = 1)
    {
        for( $y = 0; $y < self::TABLE_HEIGHT; $y++ )
        {
            $spaces_found = 0;
            $symbols_found = 0;
            $space_x = -1;

            for( $x = 0; $x < self::TABLE_WIDTH; $x++ )
            {
                if( $table[$y][$x] === $symbol )
                    $symbols_found++;
                else if( $table[$y][$x] === null )
                {
                    $spaces_found++;
                    $space_x = $x;
                }
                else break;

                if( $x === self::TABLE_WIDTH - 1 &&
                    $spaces === $spaces_found &&
                    $symbols_found === self::TABLE_WIDTH - 1 &&
                    $space_x > -1
                ){
                    return [ $y, $space_x ];
                }
            }
        }

        return false;
    }

    protected function make_vertical_line($table, $symbol, $spaces = 1)
    {
        for( $x = 0; $x < self::TABLE_WIDTH; $x++ )
        {
            $spaces_found = 0;
            $symbols_found = 0;
            $space_y = -1;

            for( $y = 0; $y < self::TABLE_HEIGHT; $y++ )
            {
                if( $table[$y][$x] === $symbol )
                    $symbols_found++;
                else if( $table[$y][$x] === null )
                {
                    $spaces_found++;
                    $space_y = $y;
                }
                else break;

                if( $y === self::TABLE_HEIGHT - 1 &&
                    $spaces === $spaces_found  &&
                    $space_y > -1
                ){
                    return [ $space_y, $x ];
                }
            }
        }

        return false;
    }

    protected function make_diagonal_line($table, $symbol, $spaces = 1)
    {
        if( $cords = $this -> make_diagonal_line1($table, $symbol, $spaces) )
            return $cords;

        if( $cords = $this -> make_diagonal_line2($table, $symbol, $spaces) )
            return $cords;

        return false;
    }

    private function make_diagonal_line1($table, $symbol, $spaces = 1)
    {
        $spaces_found = 0;
        $symbols_found = 0;
        $space_x = -1;

        for( $x = 0; $x < self::TABLE_WIDTH; $x++ )
        {
            $y = $x;

            if( $table[$y][$x] === $symbol )
                    $symbols_found++;
            else if( $table[$y][$x] === null )
            {
                $spaces_found++;
                $space_x = $x;
            }
            else break;

            if( $y === self::TABLE_HEIGHT - 1 ){
                if( $spaces === $spaces_found )
                    return [ $space_x, $space_x ];
            }

        }

        return false;
    }

    private function make_diagonal_line2($table, $symbol, $spaces = 1)
    {
        $spaces_found = 0;
        $symbols_found = 0;
        $space_x = -1;
        $space_y = -1;

        for( $x = 0; $x < self::TABLE_WIDTH; $x++ )
        {
            $y = self::TABLE_HEIGHT - $x -1;

            if( $table[$y][$x] === $symbol )
                    $symbols_found++;
            else if( $table[$y][$x] === null )
            {
                $spaces_found++;
                $space_x = $x;
                $space_y = $y;
            }
            else break;

            if( $y === self::TABLE_HEIGHT - 1 ){
                if( $spaces === $spaces_found )
                    return [ $space_y, $space_x ];
            }

        }

        return false;
    }

    /**
    * @param array $table
    * @return array|bool
    */

    protected function make_second_line_item($table)
    {
        if( $cords = $this -> make_horizontal_line($table, $this -> symbol, 2) )
            return $cords;

        if( $cords = $this -> make_vertical_line($table, $this -> symbol, 2) )
            return $cords;

        if( $cords = $this -> make_diagonal_line($table, $this -> symbol, 2) )
            return $cords;

        return false;
    }

    /**
    * @param array $table
    * @return array
    */

    protected function make_random_move($table)
    {
        $x = $y = 1;

        while ($table[$x][$y] !== null) {
            $x = rand(0, 2);
            $y = rand(0, 2);
        }

        return [$x, $y];
    }

}

