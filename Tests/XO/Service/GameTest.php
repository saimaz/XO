<?php

namespace tests\XO\Service;

use XO\Player\PlayerInterface;
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
        $table0[0][1] = PlayerInterface::SYMBOL_X;
        $table0[1][1] = PlayerInterface::SYMBOL_X;
        $table0[2][1] = PlayerInterface::SYMBOL_X;
        $out[] = [$table0, PlayerInterface::SYMBOL_X];

        // case #1
        $table1 = $table;
        $table1[0][0] = PlayerInterface::SYMBOL_X;
        $table1[1][1] = PlayerInterface::SYMBOL_X;
        $table1[2][2] = PlayerInterface::SYMBOL_X;
        $out[] = [$table1, PlayerInterface::SYMBOL_X];

        // case #2
        $table2 = $table;
        $table2[0][0] = PlayerInterface::SYMBOL_O;
        $table2[1][1] = PlayerInterface::SYMBOL_X;
        $table2[2][2] = PlayerInterface::SYMBOL_X;
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

    public function testDoTurn()
    {
        $table = array_fill(0, 3, array_fill(0, 3, null));
        $game = new Game($table);
        $game->doTurn([1, 1], PlayerInterface::SYMBOL_X);
        $result = $game->getTable();
        $table[1][1] = "X";
        $this->assertEquals($table, $result);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Field is already filled
     */
    public function testDoTurnOnExistingField()
    {
        $table = array_fill(0, 3, array_fill(0, 3, null));
        $table[1][1] = "X";
        $game = new Game($table);
        $game->doTurn([1, 1], PlayerInterface::SYMBOL_X);
        $result = $game->getTable();
        $this->assertEquals($table, $result);
    }
}
