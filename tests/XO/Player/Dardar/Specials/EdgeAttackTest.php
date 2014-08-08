<?php


namespace tests\XO\Player\Specials;


use XO\Player\Dardar\Situation;
use XO\Player\Dardar\Specials\EdgeAttack;
use XO\Player\Dardar\Specials\PandoraAttack;
use XO\Player\PlayerInterface;

class EdgeAttackTest extends \PHPUnit_Framework_TestCase
{
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
        $pandoraAttack = new EdgeAttack();
        $pandoraAttack->setSituation($situation);
        $actual = $pandoraAttack->hasOpponnetMovedEdge();
        $this->assertEquals($expected, $actual);
    }
}
