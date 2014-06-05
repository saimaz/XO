<?php


namespace tests\XO\Service;


use XO\Player\PlayerInterface;
use XO\Service\BinaryExpertPlayerService;

class BinaryExpertPlayerServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testGetState()
    {
        $service = new BinaryExpertPlayerService();

        foreach ($this->getStateTestsData() as $expectedState => $table) {
            $actualState = $service->getState($table, PlayerInterface::SYMBOL_X, PlayerInterface::SYMBOL_O);
            $this->assertSame($expectedState, $actualState);
        }
    }

    /**
     * @test
     */
    public function testGetLegalMoves()
    {
        $service = new BinaryExpertPlayerService();

        $legalMoves = 0b111111111;
        $state = 0b000000000000000000;
        $this->assertSame($legalMoves, $service->getLegalMoves($state));
        $this->assertSame(0b111111111, $service->getLegalMoves(0b000000000000000000));
        $this->assertSame(0b111111110, $service->getLegalMoves(0b000000000000000011));
        $this->assertSame(0b111111101, $service->getLegalMoves(0b000000000000001100));
        $this->assertSame(0b111111011, $service->getLegalMoves(0b000000000000110000));
        $this->assertSame(0b111111000, $service->getLegalMoves(0b000000000000111111));
        $this->assertSame(0b111111110, $service->getLegalMoves(0b000000000000000010));
        $this->assertSame(0b111111101, $service->getLegalMoves(0b000000000000001000));
        $this->assertSame(0b111111011, $service->getLegalMoves(0b000000000000100000));
        $this->assertSame(0b111111000, $service->getLegalMoves(0b000000000000101010));
        $this->assertSame(0b010111000, $service->getLegalMoves(0b110011000000101010));
    }
    
    /**
     * @test
     */
    public function testMoveRandom()
    {
        $service = new BinaryExpertPlayerService();
        $legalMoves = 0b100000001;
        $this->assertContains($service->getRandomMove($legalMoves), array(0, 8));
        $this->assertContains($service->getRandomMove(0b000000011), array(0, 1));
        $this->assertContains($service->getRandomMove(0b100000111), array(0, 1, 2, 8));
        $this->assertContains($service->getRandomMove(0b100111001), array(0, 3, 4, 5, 8));
    }

    /**
     * @test
     */
    public function testGetMoveXY()
    {
        $service = new BinaryExpertPlayerService();
        $this->assertSame([0, 0], $service->getMoveXY(0));
        $this->assertSame([0, 1], $service->getMoveXY(1));
        $this->assertSame([0, 2], $service->getMoveXY(2));
        $this->assertSame([1, 0], $service->getMoveXY(3));
        $this->assertSame([1, 1], $service->getMoveXY(4));
        $this->assertSame([1, 2], $service->getMoveXY(5));
        $this->assertSame([2, 0], $service->getMoveXY(6));
        $this->assertSame([2, 1], $service->getMoveXY(7));
        $this->assertSame([2, 2], $service->getMoveXY(8));
    }

    private function getStateTestsData()
    {
        $x = PlayerInterface::SYMBOL_X;
        $o = PlayerInterface::SYMBOL_O;

        $tableStates = array(
            0b000000000000000000 => [
                [null, null, null],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000000011 => [
                [$x, null, null],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000001100 => [
                [null, $x, null],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000110000 => [
                [null, null, $x],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000111111 => [
                [$x, $x, $x],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000000010 => [
                [$o, null, null],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000001000 => [
                [null, $o, null],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000100000 => [
                [null, null, $o],
                [null, null, null],
                [null, null, null]
            ],
            0b000000000000101010 => [
                [$o, $o, $o],
                [null, null, null],
                [null, null, null]
            ],
            0b110011000000101010 => [
                [$o, $o, $o],
                [null, null, null],
                [$x, null, $x]
            ],
        );

        return $tableStates;
    }
}
