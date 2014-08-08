<?php


namespace tests\XO\Player\Specials;


use XO\Player\Dardar\Situation;
use XO\Player\Dardar\Specials\CrossDefence;
use XO\Player\Dardar\Specials\EdgeAttack;
use XO\Player\Dardar\Specials\PandoraAttack;
use XO\Player\PlayerInterface;

class CrossDefenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function testIsPossibleProvider()
    {
        $table = [
            [null, null, null],
            ['O', 'X', null],
            [null, null, null],
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
            [null, 'X', 'X'],
            ['O', null, 'O'],
        ];
        $out[] = [false, $table];

        $table = [
            [null, null, 'O'],
            [null, 'X', null],
            ['O', null, null],
        ];
        $out[] = [true, $table];

        return $out;
    }
    /**
     * @dataProvider testIsPossibleProvider
     * @param $expected
     * @param $table
     */
    public function testIsPossible($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $attack = new CrossDefence();
        $attack->setSituation($situation);
        $actual = $attack->isPossible();
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public function testFindMoveProvider()
    {
        $table = [
            [null, null, 'O'],
            [null, 'X', null],
            ['O', null, null],
        ];
        $out[] = [[0,1], $table];


        $table = [
            ['O', null, null],
            [null, 'X', null],
            [null, null, 'O'],
        ];
        $out[] = [[0,1], $table];

        return $out;
    }
    /**
     * @dataProvider testFindMoveProvider
     * @param $expected
     * @param $table
     */
    public function testFindMove($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $attack = new CrossDefence();
        $attack->setSituation($situation);
        $actual = $attack->findMove()->getNaturalMove();
        $this->assertSame($expected, $actual);
    }
}
