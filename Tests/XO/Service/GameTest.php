<?php

namespace Tests\XO\Service;

use XO\Service\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testGetWinner()
     * @return array
     */
    public function getWinnerDataProvider()
    {
        $out = [];
        $table = array_fill(0, 3, array_fill(0, 3, null));

        // case #0
        $table0 = $table;
        $table0[0][1] = "X";
        $table0[1][1] = "X";
        $table0[2][1] = "X";
        $out[] = [$table0, "X"];

        // case #1
        $table1 = $table;
        $table1[0][0] = "X";
        $table1[1][1] = "X";
        $table1[2][2] = "X";
        $out[] = [$table1, "X"];

        // case #2
        $table2 = $table;
        $table2[0][0] = "O";
        $table2[1][1] = "X";
        $table2[2][2] = "X";
        $out[] = [$table2, null];

        return $out;
    }

    /**
     * @dataProvider getWinnerDataProvider
     * @param array $table
     * @param string|null $winner
     */
    public function testGetWinner($table, $winner = null)
    {
        $game = new Game($table);
        $this->assertEquals($winner, $game->getWinner());
    }
}
