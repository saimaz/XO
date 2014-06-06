<?php


namespace XO\Player;


use XO\Service\BinaryExpertPlayerService;

class ExpertPlayer implements PlayerInterface
{
    /**
     * @param array $table Array of current game state in 3x3 matrix
     * @param string $symbol Symbol to use to do return
     * @return array coordinates of new turn, fox example [0,1]
     */
    public function turn($table, $symbol = self::SYMBOL_X)
    {
        $move = [1, 1];
        if ($this->isLegalMove($table, $move[0], $move[1])) {
            return $move;
        }

        if ($move = $this->getWinMove($table, $symbol)) {
            return $move;
        }

        if ($move = $this->getCounterAttackMove($table, $symbol)) {
            return $move;
        }

        $move = $this->getPerfectMove($table, $symbol);
//        $move = $this->getRandomMove($table);

        return $move;
    }

    /**
     * @param $table
     * @return array
     */
    protected function getRandomMove($table)
    {
        $playerService = new BinaryExpertPlayerService();

        $state = $playerService->getState($table, PlayerInterface::SYMBOL_X, PlayerInterface::SYMBOL_O);
        $legalMoves = $playerService->getLegalMoves($state);
        $moveIndex = $playerService->getRandomMove($legalMoves);
        return $playerService->getMoveXY($moveIndex);
    }

    protected function getPerfectMove($table, $symbol = 'X')
    {
        $playerService = new BinaryExpertPlayerService();

        $state = $playerService->getState($table, PlayerInterface::SYMBOL_X, PlayerInterface::SYMBOL_O);
        $turn = -1;
        if ($symbol === PlayerInterface::SYMBOL_X) {
            $turn = 1;
        }

        $legalMoves = $playerService->getPerfectMove($state, $turn);
        $moveXY = $playerService->getMoveXY($legalMoves);
        return $moveXY;
    }

    /**
     * @param $table
     * @param $moveRow
     * @param $moveColumn
     * @return bool
     */
    protected function isLegalMove($table, $moveRow, $moveColumn)
    {
        return null === $table[$moveRow][$moveColumn];
    }

    protected function getWinMove($table, $symbol)
    {
        for ($rowNr = 0; $rowNr <= 2; $rowNr++) {
            if ($move = $this->getRowWinMove($table[$rowNr], $symbol, $rowNr)) {
                return $move;
            }
        }

        if ($move = $this->getCrossWinMove($table, $symbol)) {
            return $move;
        }

        return null;
    }

    protected function getCounterAttackMove($table, $symbol)
    {
        $enemySymbol = PlayerInterface::SYMBOL_X;
        if ($symbol === PlayerInterface::SYMBOL_X) {
            $enemySymbol = PlayerInterface::SYMBOL_O;
        }

        return $this->getWinMove($table, $enemySymbol);
    }

    protected function getRowWinMove($row, $symbol, $rowNr)
    {
        if ($row[0] === $symbol && $row[1] === $symbol && $row[2] === null) {
            return [$rowNr, 2];
        }
        if ($row[0] === $symbol && $row[1] === null && $row[2] === $symbol) {
            return [$rowNr, 1];
        }
        if ($row[0] === null && $row[1] === $symbol && $row[2] === $symbol) {
            return [$rowNr, 0];
        }
        
        return null;
    }

    protected function getCrossWinMove($table, $symbol)
    {
        if ($table[0][0] === $symbol && $table[1][1] === $symbol && $table[2][2] === null) {
            return [2, 2];
        }
        if ($table[0][0] === $symbol && $table[1][1] === null && $table[2][2] === $symbol) {
            return [1, 1];
        }
        if ($table[0][0] === null && $table[1][1] === $symbol && $table[2][2] === $symbol) {
            return [0, 0];
        }

        if ($table[0][2] === $symbol && $table[1][1] === $symbol && $table[2][0] === null) {
            return [2, 0];
        }
        if ($table[0][2] === $symbol && $table[1][1] === null && $table[2][0] === $symbol) {
            return [1, 1];
        }
        if ($table[0][2] === null && $table[1][1] === $symbol && $table[2][0] === $symbol) {
            return [0, 2];
        }
    }
}
