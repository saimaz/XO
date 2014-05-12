<?php

use XO\Game;

class GameTest extends PHPUnit_Framework_TestCase {

    protected $game;

    public function setUp()
    {
        $this->game = new Game();
    }

    public function testStart()
    {
        // first roll out
        $this->game->roll();
    }
}
 