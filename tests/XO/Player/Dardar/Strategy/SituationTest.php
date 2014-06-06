<?php
namespace tests\XO\Player\Dardar;

use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\SituationCounter;
use XO\Player\PlayerInterface;
use XO\Player\DardarPlayer;
use XO\Player\Dardar\Situation;
use XO\Service\Game;
use XO\Utilities\TableHelper;

class SituationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIsTurnException()
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->isMove($situation->createMove([null, null]));

        $actual = $situation->isMove($situation->createMove(['x', 'x']));

        $actual = $situation->isMove($situation->createMove([false, true]));

        $actual = $situation->isMove($situation->createMove([1, null]));

    }

    public function testIsTurn()
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);



        $actual = $situation->isMove($situation->createMove([1, 1]));
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
    public function testFindDefendCrossMoveProvider()
    {
        $table = [
            [null, null, 'O'],
            [null, 'O', null],
            [null, null, null],
        ];
        $out[] = [[0,2], $table];

        $table = [
            [null, null, null],
            [null, 'O', null],
            ['O', null, null],
        ];
        $out[] = [[2, 0], $table];

        $table = [
            [null, null, null],
            [null, 'O', null],
            [null, null, 'O'],
        ];
        $out[] = [[0, 0], $table];

        $table = [
            ['O', null, null],
            [null, 'O', null],
            [null, null, null],
        ];
        $out[] = [[2, 2], $table];

        $table = [
            [null, 'O', null],
            [null, 'O', null],
            [null, null, null],
        ];
        $out[] = [null, $table];

        return $out;
    }

    /**
     * @dataProvider testFindDefendCrossMoveProvider
     * @param $expected
     * @param $table
     * @group this
     */
    public function testFindDefendCrossMove($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);

        try {
            $actual = $situation->findDefendCrossMove()->getNaturalMove();
        } catch (MoveNotFoundException $e) {
            $actual = null;
        }

        $this->assertEquals($expected, $actual);
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
    public function IsCrossEdgeMove($expected, $table)
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
            [null, 'O', null],
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
    public function countWithFilterProvider()
    {
        $line = ['O', 'X', 'X'];
        $out[] = [2, SituationCounter::ME, $line];

        $line = ['O', 'O', 'X'];
        $out[] = [2, SituationCounter::HE, $line];

        $line = ['O', null, 'X'];
        $out[] = [1, SituationCounter::EMPTIES, $line];

        $line = ['O', null, 'X'];
        $out[] = [1, SituationCounter::ME, $line];

        $line = ['O', null, 'X'];
        $out[] = [1, SituationCounter::HE, $line];

        $line = ['O', null, 'X'];
        $out[] = [1, SituationCounter::ME, $line];

        $line = ['O', null, null];
        $out[] = [0, SituationCounter::ME, $line];

        $line = ['X', null, null];
        $out[] = [0, SituationCounter::HE, $line];

        return $out;
    }
    /**
     * @dataProvider countWithFilterProvider
     * @param $expected
     * @param $type
     * @param $line
     * @group filters
     */
    public function testCountWithFilter($expected, $type, $line)
    {
        $table = new TableHelper();
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->countBy($line, $type);
        $this->assertEquals($expected, $actual);
    }


    /**
     * @return array
     */
    public function testCountTableMovesByProvider()
    {
        $table = [
            ['O', null, null],
            [null, 'X', 'O'],
            [null, null, 'O'],
        ];
        $out[] = [5, null, $table];

        $table = [
            ['X', null, null],
            [null, 'X', 'O'],
            [null, null, 'O'],
        ];
        $out[] = [2, 'X', $table];

        $table = [
            ['X', null, 'O'],
            [null, 'X', null],
            [null, null, 'O'],
        ];
        $out[] = [2, 'X', $table];

        $table = [
            ['X', null, null],
            [null, 'X', null],
            [null, null, 'O'],
        ];
        $out[] = [1, 'O', $table];

        return $out;
    }
    /**
     * @dataProvider testCountTableMovesByProvider
     * @param $expected
     * @param $symbol
     * @param $table
     * @group counters
     */
    public function testCountTableMovesBy($expected, $symbol, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $actual = $situation->countTableMovesBy($symbol);
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
