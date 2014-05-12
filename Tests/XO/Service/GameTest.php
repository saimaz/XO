<?php

namespace Tests\XO\Service;


use XO\Service\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param array $table
     * @param null $winner
     */
    public function testGetWinner($table, $winner = null)
    {
        $game = new Game($table);

        $this->assertEquals($winner, $game->getWinner());
    }
}
