<?php
namespace tests\XO\Player\Dardar;

use XO\Player\PlayerInterface;
use XO\Player\DardarPlayer;
use XO\Player\Dardar\Situation;
use XO\Service\Game;
use XO\Utilities\TableHelper;

class SituationTest extends \PHPUnit_Framework_TestCase
{
    public function testIsTurn()
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);

        $actual = $situation->isTurn([null, null]);
        $this->assertEquals(false, $actual);

        $actual = $situation->isTurn(['x', 'x']);
        $this->assertEquals(false, $actual);

        $actual = $situation->isTurn([false, true]);
        $this->assertEquals(false, $actual);

        $actual = $situation->isTurn([1, null]);
        $this->assertEquals(false, $actual);

        $actual = $situation->isTurn([1, 1]);
        $this->assertEquals(true, $actual);
    }

    public function testCountBy()
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);

        $actual = $situation->countBy(['X', 'O', 'X'], 'my');
        $this->assertEquals(2, $actual);

        $actual = $situation->countBy([null, 'O', 'X'], 'enemy');
        $this->assertEquals(1, $actual);
    }

    /**
     * @return array
     */
    public function testIsCrossEdgeMoveProvider()
    {
        $table = [
            ['O', null, null],
            [null, 'X', 'O'],
            [null, null, 'O'],
        ];
        $out[] = [false, $table];

        $table = [
            ['X', null, null],
            [null, 'X', 'O'],
            [null, null, 'O'],
        ];
        $out[] = [true, $table];

        $table = [
            ['X', null, 'O'],
            [null, 'X', null],
            [null, null, 'O'],
        ];
        $out[] = [false, $table];

        $table = [
            ['X', null, null],
            [null, 'X', null],
            [null, null, 'O'],
        ];
        $out[] = [false, $table];

        return $out;
    }
    /**
     * @dataProvider testIsCrossEdgeMoveProvider
     * @param $expected
     * @param $table
     * @group this
     */
    public function testIsCrossEdgeMove($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->IsCrossEdgeMove();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function isCoordsNeighboursData()
    {
        $table = [
            ['O', 'O', null],
            [null, null, null],
            [null, null, null],
        ];
        $out[] = [true, [0, 0], [1, 0]];

        $out[] = [true, [0, 1], [0, 2]];

        $out[] = [true, [0, 0], [0, 1]];

        $table = [
            [null, null, null],
            [null, null, 'O'],
            [null, null, 'O'],
        ];
        $out[] = [true, [2, 1], [2, 2]];

        $table = [
            [null, null, null],
            [null, null, null],
            [null, 'O', 'O'],
        ];
        $out[] = [true, [1, 2], [2, 2]];

        $table = [
            [null, null, 'O'],
            [null, null, null],
            [null, null, 'O'],
        ];
        $out[] = [false, [2, 0], [2, 2]];

        $table = [
            [null, null, null],
            [null, '0', null],
            [null, null, 'O'],
        ];
        $out[] = [false, [1, 1], [2, 2]];

        return $out;
    }
    /**
     * @dataProvider isCoordsNeighboursData
     * @param $expected
     * @param $move1
     * @param $move2
     */
    public function testisCoordsNeighbours($expected, $move1, $move2)
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->isCoordsNeighbours($move1, $move2);
        $this->assertEquals($expected, $actual);
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
     * @dataProvider testCountMoveProvider
     * @param $expected
     * @param $table
     */
    public function testCountMove($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->countMoves();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function isPandoraMoveProvider()
    {

        $table = [
            [null, 'O', null],
            ['O', 'X', null],
            [null, null, null],
        ];
        $out[] = [true, $table];

        $table = [
            [null, null, 'X'],
            ['O', 'X', null],
            [null, 'O', null],
        ];
        $out[] = [false, $table];

        $table = [
            [null, null, null],
            [null, 'X', null],
            ['O', null, null],
        ];
        $out[] = [false, $table];

        $table = [
            [null, null, 'O'],
            [null, 'X', null],
            ['O', 'O', null],
        ];
        $out[] = [false, $table];

        return $out;
    }
    /**
     * @dataProvider isPandoraMoveProvider
     * @param $expected
     * @param $table
     * @group patterns
     */
    public function testIsPandoraMoveProvider($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->isPandoraMove(PlayerInterface::SYMBOL_O, PlayerInterface::SYMBOL_X);
        $this->assertEquals($expected, $actual);
    }





    /**
     * @return array
     */
    public function hasPatternProvider()
    {
        $pattern = ['O', 'X', 'X'];
        $line = ['O', 'X', 'X'];
        $out[] = [true, $pattern, $line];

        $line = ['O', 'O', 'X'];
        $out[] = [false, $pattern, $line];

        $line = ['O', null, 'X'];
        $out[] = [false, $pattern, $line];

        return $out;
    }
    /**
     * @dataProvider hasPatternProvider
     * @param $expected
     * @param $pattern
     * @param $line
     * @group patterns
     */
    public function testHasPattern($expected, $pattern, $line)
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->hasPattern($line, $pattern);
        $this->assertEquals($expected, $actual);
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
     * @dataProvider testIsEdgeProvider
     * @param $expected
     * @param $table
     */
    public function testIsEdge($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->opponnetMovedEdge();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @group counters
     */
    public function testareValuesNear()
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->areValuesNear(0, 1);
        $this->assertEquals(true, $actual);

        $actual = $situation->areValuesNear(1, 2);
        $this->assertEquals(true, $actual);

        $actual = $situation->areValuesNear(2, 1);
        $this->assertEquals(true, $actual);

        $actual = $situation->areValuesNear(2, 2);
        $this->assertEquals(false, $actual);
    }
}
