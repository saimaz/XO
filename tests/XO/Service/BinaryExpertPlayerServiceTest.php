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

    /**
     * @test
     */
    public function testDetectWin()
    {
        $service = new BinaryExpertPlayerService();
        $state = 0b000000000000000000;
        $this->assertSame(0b000000000000000000, $service->detectWin($state));

        $this->assertSame(0b0100111111000000000000, $service->detectWin(0b111111000000000000));
        $this->assertSame(0b1000101010000000000000, $service->detectWin(0b101010000000000000));
        $this->assertSame(0b0100000000111111000000, $service->detectWin(0b000000111111000000));
        $this->assertSame(0b1000000000101010000000, $service->detectWin(0b000000101010000000));
        $this->assertSame(0b0100000000000000111111, $service->detectWin(0b000000000000111111));
        $this->assertSame(0b1000000000000000101010, $service->detectWin(0b000000000000101010));
        $this->assertSame(0b0100000011000011000011, $service->detectWin(0b000011000011000011));
        $this->assertSame(0b1000000010000010000010, $service->detectWin(0b000010000010000010));
        $this->assertSame(0b0100001100001100001100, $service->detectWin(0b001100001100001100));
        $this->assertSame(0b1000001000001000001000, $service->detectWin(0b001000001000001000));
        $this->assertSame(0b0100110000110000110000, $service->detectWin(0b110000110000110000));
        $this->assertSame(0b1000100000100000100000, $service->detectWin(0b100000100000100000));
        $this->assertSame(0b0100000011001100110000, $service->detectWin(0b000011001100110000));
        $this->assertSame(0b1000000010001000100000, $service->detectWin(0b000010001000100000));
        $this->assertSame(0b0100110000001100000011, $service->detectWin(0b110000001100000011));
        $this->assertSame(0b1000100000001000000010, $service->detectWin(0b100000001000000010));
        // $this->assertSame(0b1100000000000000000000, $service->detectWin(0b101010101010101010));
    }

    /**
     * @test
     */
    public function testDetectWinMove()
    {
        $serviceMock = $this->getMock(
            '\XO\Service\BinaryExpertPlayerService',
            array('detectWin')
        );
        $serviceMock->expects($this->any())->method('detectWin')->willReturnArgument(0);

        $state = 0b000000000000000000;
        $moveIndex = 0; // 0 .. 8
        $playerTurn = 1; // -1 (0) or 1 (X)
        $this->assertSame(0b000000000000000011, $serviceMock->detectWinMove($state, $moveIndex, $playerTurn));

        $playerTurn = -1; // -1 (0) or 1 (X)
        $this->assertSame(0b000000000000000010, $serviceMock->detectWinMove($state, $moveIndex, $playerTurn));


        $state = 0b111000000000000000;
        $moveIndex = 0; // 0 .. 8
        $playerTurn = 1; // -1 (0) or 1 (X)
        $this->assertSame(0b111000000000000011, $serviceMock->detectWinMove($state, $moveIndex, $playerTurn));
    }
    
    /**
     * @test
     */
    public function testOpeningBook()
    {
        $service = new BinaryExpertPlayerService();
        $state = 0b000000000000000000;
//        $this->assertSame(0b111111111, $service->openingBook($state));
        $this->assertSame(0b101010101, $service->openingBook($state));
        $this->assertSame(0b101000101, $service->openingBook(0b000000001000000000));
        $this->assertSame(0b000010000, $service->openingBook(0b000000000000000010));
        $this->assertSame(0b000010000, $service->openingBook(0b000000000000100000));
        $this->assertSame(0b000010000, $service->openingBook(0b000010000000000000));
        $this->assertSame(0b000010000, $service->openingBook(0b100000000000000000));
        $this->assertSame(0b010010101, $service->openingBook(0b000000000000001000));
        $this->assertSame(0b001110001, $service->openingBook(0b000000000010000000));
        $this->assertSame(0b100011100, $service->openingBook(0b000000100000000000));
        $this->assertSame(0b101010010, $service->openingBook(0b001000000000000000));

        $this->assertSame(0b101000101, $service->openingBook(0b000000001100000000));
        $this->assertSame(0b000010000, $service->openingBook(0b000000000000000011));
        $this->assertSame(0b000010000, $service->openingBook(0b000000000000110000));
        $this->assertSame(0b000010000, $service->openingBook(0b000011000000000000));
        $this->assertSame(0b000010000, $service->openingBook(0b110000000000000000));
        $this->assertSame(0b010010101, $service->openingBook(0b000000000000001100));
        $this->assertSame(0b001110001, $service->openingBook(0b000000000011000000));
        $this->assertSame(0b100011100, $service->openingBook(0b000000110000000000));
        $this->assertSame(0b101010010, $service->openingBook(0b001100000000000000));
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
