<?php
namespace tests\XO\Player;

use XO\Player\PlayerInterface;
use XO\Player\DrunkPlayer;
use XO\Service\Game;

class DrunkPlayerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Counts hited elements count
     * @param $table
     * @return int
     */
    protected function countSymbols($table)
    {
        $result = 0;
        foreach ([0, 1, 2] as $row) {
            $result += count(array_filter($table[$row], "is_string"));
        }
        return $result;
    }

    /**
     * Finds first empty spot and returns coodrinates
     * @param $table
     * @return array|null
     */
    protected function findEmptySpot($table)
    {
        for ($x = 0; $x < 3; $x++) {
            for ($y = 0; $y < 3; $y++) {
                if ($table[$x][$y] === null) {
                    return [$x, $y];
                }
            }
        }

        return null;
    }

    public function testTurns()
    {
        $randomPlayer = new DrunkPlayer();
        $game = new Game();
        $game->addPlayer($randomPlayer, PlayerInterface::SYMBOL_X);
        $table = $game->getTurn();

        // First turn will be only in middle
        $this->assertEquals(1, $this->countSymbols($table));
        $this->assertEquals(PlayerInterface::SYMBOL_X, $table[1][1]);

        $table = $game->doTurn($this->findEmptySpot($table), PlayerInterface::SYMBOL_O);

        // we know that our turn definitely will be in corner
        $this->assertEquals(2, $this->countSymbols($table));
        $this->assertEquals(PlayerInterface::SYMBOL_O, $table[0][0]);

        $table = $game->getTurn();
        $this->assertEquals(3, $this->countSymbols($table));

        for ($x = 4; $x < 9; $x += 2) {
            if (!$game->getWinner()) {
                $table = $game->doTurn($this->findEmptySpot($table), PlayerInterface::SYMBOL_O);
                $this->assertEquals($x, $this->countSymbols($table));
            }
            if (!$game->getWinner()) {
                $table = $game->getTurn();
                $this->assertEquals($x + 1, $this->countSymbols($table));
            }
        }
    }
}
