<?php
namespace XO\Player;


class WanisPlayer implements PlayerInterface
{

    /**
     * @param array $table Array of current game state in 3x3 matrix
     * @param string $symbol Symbol to use to do return
     * @return array coordinates of new turn, fox example [0,1]
     */
    public function turn($table, $symbol = self::SYMBOL_X)
    {
        switch ($symbol) {
            case self::SYMBOL_O:
                $opposite = self::SYMBOL_X;
                break;
            case self::SYMBOL_X:
                $opposite = self::SYMBOL_O;
        }

        $strategy = $this->corners($table, $opposite);
        if ($strategy) {
            return $strategy;
        }
        do {
            $x = rand(0, 2);
            $y = rand(0, 2);

        } while ($table[$x][$y] !== null);

        return [$x, $y];

    }

    private function corners($table, $opposite)
    {
        $corners = [
             [0,0], [2,2], [0,2], [2,0]
        ];
        shuffle($corners);
        $opp = $corners;
        do {
            $test = array_pop($opp);
            if ($table[$test[0]][$test[1]] == $opposite) {

            }
            $mine = $corners;
            do {
                $test2 = array_pop($mine);
                if ($table[$test2[0]][$test2[1]] == null) {
                    return $test2;
                }
            } while (count($mine));
        } while (count($opp));
        return null;
    }
}
