<?php
namespace tests\XO\Player;

use XO\Player\PlayerInterface;
use XO\Player\DardarPlayer;
use XO\Player\Dardar\Situation;
use XO\Service\Game;
use XO\Utilities\TableHelper;

class DardarPlayerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsWorking()
    {
        $this->assertEquals(1, 1);
    }

    protected function defenceStrategyProvider()
    {
        $out = [];
        $table = [
            ['X', 'O', 'X'],
            [null, 'X', 'O'],
            ['O', 'X', null],
        ];

        $out[] = [[2,2], $table];
        return $out;
    }

}
