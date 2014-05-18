<?php
namespace tests\XO\Player\Strategy;

use XO\Player\PlayerInterface;
use XO\Player\DardarPlayer;
use XO\Player\Strategy\GameActions;
use XO\Service\Game;
use XO\Utilities\TableHelper;

class GameActionsTest extends \PHPUnit_Framework_TestCase
{
    public function testIsTurn()
    {
        $table = new TableHelper();
        $gameActions = new GameActions($table, PlayerInterface::SYMBOL_X);

        $actual = $gameActions->isTurn([null, null]);
        $this->assertEquals(false, $actual);

        $actual = $gameActions->isTurn(['x', 'x']);
        $this->assertEquals(false, $actual);

        $actual = $gameActions->isTurn([false, true]);
        $this->assertEquals(false, $actual);

        $actual = $gameActions->isTurn([1, null]);
        $this->assertEquals(false, $actual);

        $actual = $gameActions->isTurn([1, 1]);
        $this->assertEquals(true, $actual);
    }

    public function testCountBy()
    {
        $table = new TableHelper();
        $gameActions = new GameActions($table, PlayerInterface::SYMBOL_X);

        $actual = $gameActions->countBy(['X', 'O', 'X'], 'my');
        $this->assertEquals(2, $actual);

        $actual = $gameActions->countBy([null, 'O', 'X'], 'enemy');
        $this->assertEquals(1, $actual);
    }

    /**
     * @return array
     */
    public function testCountMoveProvider()
    {
        $table = [
            [null, null, null],
            ['X', 'O', null],
            [null, null, null],
        ];
        $out[] = [2, $table];

        $table = [
            [null, null, null],
            ['X', 'O', null],
            [null, 'X', null],
        ];
        $out[] = [3, $table];

        return $out;
    }

    /**
     * @return array
     */
    public function testIsEdgeProvider()
    {
        $table = [
            [null, null, null],
            ['O', 'X', null],
            [null, null, null],
        ];
        $out[] = [true, $table];

        $table = [
            [null, null, null],
            [null, 'X', null],
            ['O', null, null],
        ];
        $out[] = [false, $table];

        return $out;
    }

    /**
     * @dataProvider testCountMoveProvider
     * @param $expected
     * @param $table
     */
    public function testCountMove($expected, $table)
    {
        $gameActions = new GameActions($table, PlayerInterface::SYMBOL_X);
        $actual = $gameActions->countMoves();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider testIsEdgeProvider
     * @param $expected
     * @param $table
     */
    public function testIsEdge($expected, $table)
    {
        $gameActions = new GameActions($table, PlayerInterface::SYMBOL_X);
        $actual = $gameActions->isEdgeMove();
        $this->assertEquals($expected, $actual);
    }
}
