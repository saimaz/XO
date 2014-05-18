<?php
namespace XO\Player;

class EvilMagePlayer implements PlayerInterface
{
    protected $symbol = self::SYMBOL_X;
    const TABLE_SIZE = 3;

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

    /**
     * @param $table
     * @param $symbol
     * @param int $spaces
     *
     * @return array|bool
     */

    protected function makeHorizontalLine($table, $symbol, $spaces = 1)
    {
        for ($y = 0; $y < self::TABLE_SIZE; $y++) {

            $cords = $this -> makeOneHorizontalLine($table, $symbol, $spaces, $y);

            if ($cords !== false) {
                return $cords;
            }
        }

        return false;
    }

    private $empty_position = [
        'space' => 0,
        'symbol' => 0,
        'space_at' => [ -1, -1 ]
    ];

    /**
     * @param array $table
     * @param string $symbol
     * @param int $spaces
     * @param int $y
     *
     * @return bool|array
     */

    private function makeOneHorizontalLine($table, $symbol, $spaces, $y)
    {
        $positions = $this -> empty_position;

        for ($x = 0; $x < self::TABLE_SIZE; $x++) {
            $positions = $this -> populatePositionData($table, $symbol, $x, $y, $positions);
        }

        if ($this -> positionsFound($positions, $spaces)) {
            return $positions['space_at'];
        }

        return false;
    }

    /**
     * @param array $positions
     * @param int $spaces
     *
     * @return bool
     */

    private function positionsFound($positions, $spaces)
    {
        return ($positions['space'] === $spaces &&
            $positions['symbol'] === (self::TABLE_SIZE -$spaces) &&
            $positions['space_at'][0] > -1 &&
            $positions['space_at'][1] > -1);
    }

    protected function makeVerticalLine($table, $symbol, $spaces = 1)
    {
        for ($x = 0; $x < self::TABLE_SIZE; $x++) {

            $cords = $this -> makeOneVerticalLine($table, $symbol, $spaces, $x);

            if ($cords !== false) {
                return $cords;
            }
        }

        return false;
    }

    private function makeOneVerticalLine($table, $symbol, $spaces, $x)
    {
        $positions = $this -> empty_position;

        for ($y = 0; $y < self::TABLE_SIZE; $y++) {
            $positions = $this -> populatePositionData($table, $symbol, $x, $y, $positions);
        }

        if ($this -> positionsFound($positions, $spaces)) {
            return $positions['space_at'];
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
        $positions = $this -> empty_position;

        for ($x = 0; $x < self::TABLE_SIZE; $x++) {
            $y = $x;

            $positions = $this -> populatePositionData($table, $symbol, $x, $y, $positions);

            if ($this -> positionsFound($positions, $spaces)) {
                return $positions['space_at'];
            }
        }

        return false;
    }

    private function makeDiagonalLine2($table, $symbol, $spaces = 1)
    {
        $positions = $this -> empty_position;

        for ($x = 0; $x < self::TABLE_SIZE; $x++) {
            $y = self::TABLE_SIZE - $x -1;

            $positions = $this -> populatePositionData($table, $symbol, $x, $y, $positions);

            if ($this -> positionsFound($positions, $spaces)) {
                return $positions['space_at'];
            }

        }

        return false;
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

    /**
     * @param $table
     * @param $symbol
     * @param $x
     * @param $y
     * @param $positions
     *
     * @return mixed
     */
    private function populatePositionData($table, $symbol, $x, $y, $positions)
    {
        if ($table[$y][$x] === $symbol) {
            $positions['symbol']++;
            return $positions;
        } elseif ($table[$y][$x] === null) {
            $positions['space']++;
            $positions['space_at'] = [$y, $x];
            return $positions;
        }
        return $positions;
    }
}
