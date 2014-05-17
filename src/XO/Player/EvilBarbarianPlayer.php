<?php
namespace XO\Player;

//require_once("PlayerInterface.php");

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

        if ($cords = $this -> makeWinningMove($table)) {
            return $cords;
        }

        if ($cords = $this -> makeHalfWin($table)) {
            return $cords;
        }

        return $this -> makeRandomMove($table);
    }

    /**
    * @param array $table
    * @return array|bool
    */

    protected function makeWinningMove($table)
    {
        if ($cords = $this -> makeHorizontalLine($table, $this -> symbol, 1)) {
            return $cords;
        }

        if ($cords = $this -> makeVerticalLine($table, $this -> symbol, 1)) {
            return $cords;
        }

        if ($cords = $this -> makeDiagonalLine($table, $this -> symbol, 1)) {
            return $cords;
        }

        return false;
    }

    protected function makeHorizontalLine($table, $symbol, $spaces = 1)
    {
        for ($y = 0; $y < self::TABLE_HEIGHT; $y++) {
            $spaces_found = 0;
            $symbols_found = 0;
            $space_x = -1;

            for ($x = 0; $x < self::TABLE_WIDTH; $x++) {
                if ($table[$y][$x] === $symbol) {
                    $symbols_found++;
                }
                elseif ($table[$y][$x] === null) {
                    $spaces_found++;
                    $space_x = $x;
                }
                else {
                    break;
                }

                if ($x === self::TABLE_WIDTH - 1 &&
                    $spaces === $spaces_found &&
                    $symbols_found === self::TABLE_WIDTH - 1 &&
                    $space_x > -1) {
                    return [ $y, $space_x ];
                }
            }
        }

        return false;
    }

    protected function makeVerticalLine($table, $symbol, $spaces = 1)
    {
        for ($x = 0; $x < self::TABLE_WIDTH; $x++) {
            $spaces_found = 0;
            $symbols_found = 0;
            $space_y = -1;

            for ($y = 0; $y < self::TABLE_HEIGHT; $y++) {
                if ($table[$y][$x] === $symbol) {
                    $symbols_found++;
                }
                elseif ($table[$y][$x] === null) {
                    $spaces_found++;
                    $space_y = $y;
                }
                else {
                    break;
                }

                if ($y === self::TABLE_HEIGHT - 1 &&
                    $spaces === $spaces_found  &&
                    $space_y > -1) {
                    return [ $space_y, $x ];
                }
            }
        }

        return false;
    }

    protected function makeDiagonalLine($table, $symbol, $spaces = 1)
    {
        if ($cords = $this -> makeDiagonalLine1($table, $symbol, $spaces)) {
            return $cords;
        }

        if ($cords = $this -> makeDiagonalLine2($table, $symbol, $spaces)) {
            return $cords;
        }

        return false;
    }

    private function makeDiagonalLine1($table, $symbol, $spaces = 1)
    {
        $spaces_found = 0;
        $symbols_found = 0;
        $space_x = -1;

        for ($x = 0; $x < self::TABLE_WIDTH; $x++) {
            $y = $x;

            if ($table[$y][$x] === $symbol) {
                $symbols_found++;
            }
            elseif ($table[$y][$x] === null) {
                $spaces_found++;
                $space_x = $x;
            }
            else {
                break;
            }

            if ($y === self::TABLE_HEIGHT - 1) {
                if ($spaces === $spaces_found) {
                    return [ $space_x, $space_x ];
                }
            }

        }

        return false;
    }

    private function makeDiagonalLine2($table, $symbol, $spaces = 1)
    {
        $spaces_found = 0;
        $symbols_found = 0;
        $space_x = -1;
        $space_y = -1;

        for ($x = 0; $x < self::TABLE_WIDTH; $x++) {
            $y = self::TABLE_HEIGHT - $x -1;

            if ($table[$y][$x] === $symbol) {
                $symbols_found++;
            }
            elseif ($table[$y][$x] === null) {
                $spaces_found++;
                $space_x = $x;
                $space_y = $y;
            }
            else {
                break;
            }

            if ($y === self::TABLE_HEIGHT - 1) {
                if ($spaces === $spaces_found) {
                    return [ $space_y, $space_x ];
                }
            }

        }

        return false;
    }

    /**
    * @param array $table
    * @return array|bool
    */

    protected function makeHalfWin($table)
    {
        if ($cords = $this -> makeHorizontalLine($table, $this -> symbol, 2)) {
            return $cords;
        }

        if ($cords = $this -> makeVerticalLine($table, $this -> symbol, 2)) {
            return $cords;
        }

        if ($cords = $this -> makeDiagonalLine($table, $this -> symbol, 2)) {
            return $cords;
        }

        return false;
    }

    /**
    * @param array $table
    * @return array
    */

    protected function makeRandomMove($table)
    {
        $x = $y = 1;

        while ($table[$x][$y] !== null) {
            $x = rand(0, 2);
            $y = rand(0, 2);
        }

        return [$x, $y];
    }
}
