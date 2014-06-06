<?php


namespace tests\XO\Player\Specials;


use XO\Player\Dardar\Situation;
use XO\Player\Dardar\Specials\EdgeAttack;
use XO\Player\Dardar\Specials\PandoraAttack;
use XO\Player\PlayerInterface;

class PandoraAttackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function testPandoraAttackProvider()
    {
        $table = [
            [null, null, null],
            ['X', 'O', null],
            [null, null, null],
        ];
        $out[] = [true, $table];

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

        return $out;
    }
    /**
     * @dataProvider testPandoraAttackProvider
     * @param $expected
     * @param $table
     */
    public function testIsPossible($expected, $table)
    {
        $situation = new Situation($table, PlayerInterface::SYMBOL_X);
        $attack = new PandoraAttack();
        $attack->setSituation($situation);
        $actual = $attack->isPossible();
        $this->assertSame($expected, $actual);
    }
}
