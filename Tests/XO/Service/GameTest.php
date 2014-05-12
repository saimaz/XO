<?php

namespace Tests\XO\Service;

use XO\Service\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{

    public function getWinnerDataProvider()
    {
        return array(
            array(array(), false),
            array(array(), false),
        );
    }

    /**
     * @param array $table
     * @param null $winner
     */
    public function testGetWinner($table, $winner = null)
    {
        $game = new Game($table);

        $this->assertEquals($winner, $game->getWinner());
    }

//    public function testFirstAttempt()
}
