<?php


namespace tests\XO\Player;


use XO\Player\ExpertPlayer;
use XO\Player\PlayerInterface;

class ExpertPlayerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testExpertPlayerExists()
    {
        $expertPlayer = new ExpertPlayer();
    }

    /**
     * @test
     */
    public function testUseWinPossibility()
    {
        $expertPlayer = new ExpertPlayer();
        foreach ($this->getWinXPossibilityTables() as $tableMove) {
            $table = $tableMove[0];
            $expectedMove = $tableMove[1];
            $this->assertSame($expectedMove, $expertPlayer->turn($table, PlayerInterface::SYMBOL_X));
        }
    }

    /**
     * @test
     */
    public function testUseCounterAttackPossibility()
    {
        $expertPlayer = new ExpertPlayer();
        foreach ($this->getCounterAttackOPossibilityTables() as $tableMove) {
            $table = $tableMove[0];
            $expectedMove = $tableMove[1];
            $this->assertSame($expectedMove, $expertPlayer->turn($table, PlayerInterface::SYMBOL_O));
        }
    }

    private function getWinXPossibilityTables()
    {
        $tables = array(
            array(
                [
                    ['O', 'O', null],
                    [null, 'X', null],
                    ['X', null, null],
                ],
                [0, 2]
            ),
            array(
                [
                    ['O', 'O', null],
                    ['X', 'X', null],
                    [null, null, null],
                ],
                [1, 2]
            ),
            array(
                [
                    ['O', 'O', null],
                    [null, 'X', null],
                    ['X', 'O', 'X'],
                ],
                [0, 2]
            ),
            array(
                [
                    ['O', null, null],
                    [null, 'O', null],
                    ['X', null, 'X'],
                ],
                [2, 1]
            ),
        );

        return $tables;
    }

    private function getCounterAttackOPossibilityTables()
    {
        $tables = array(
            array(
                [
                    ['O', null, null],
                    [null, 'X', null],
                    ['X', 'O', null],
                ],
                [0, 2]
            ),
            array(
                [
                    ['O', null, null],
                    ['X', 'X', null],
                    [null, 'O', null],
                ],
                [1, 2]
            ),
            array(
                [
                    ['O', null, null],
                    ['O', 'X', null],
                    ['X', 'O', 'X'],
                ],
                [0, 2]
            ),
            array(
                [
                    ['O', null, null],
                    [null, 'O', null],
                    ['X', null, 'X'],
                ],
                [2, 1]
            ),
        );

        return $tables;
    }
}
