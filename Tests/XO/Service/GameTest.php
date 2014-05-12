<?php

namespace Tests\XO\Service;


use XO\Service\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param array $table
     */
    public function testGetWinner($table)
    {
        $game = new Game($table);
    }
}
