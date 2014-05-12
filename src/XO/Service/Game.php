<?php

namespace XO\Service;

#########################################################################################################
#        Table Game XO
#
#   Here's the board. Each square is marked as coordinates which must understand XO robot.
# When someone makes turn, robot returns number with his turn. Of course robot should always win ;).
# f.e. O is defined as [1,3] array and X is [2,2].
#
#       +---+---+---+
#       | 1 | 2 | 3 |
#   +---+---+---+---+
#   | 1 |   |   | O |
#   +---+---+---+---+
#   | 2 |   | X |   |
#   +---+---+---+---+
#   | 3 |   |   |   |
#   +---+---+---+---+
#########################################################################################################

use XO\Player\PlayerInterface;

/**
 * This class is the main service to handle game process
 */
class Game
{
    /**
     * @var array
     */
    protected $table;

    /**
     * @var array
     */
    protected $strategies = [];

    /**
     * @param array $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * @param PlayerInterface $strategy
     * @param string $symbol
     */
    public function setPlayer(PlayerInterface $strategy, $symbol = PlayerInterface::SYMBOL_X)
    {
        $this->strategies[$symbol] = $strategy;
    }

    /**
     * @param array $turn
     * @param string $symbol
     */
    public function doTurn($turn, $symbol = PlayerInterface::SYMBOL_X)
    {
        $this->table[$turn[0]][$turn[1]] = $symbol;
    }

    /**
     * Returns symbol of winner in case we have a winner
     * @return null|string
     */
    public function getWinner()
    {
        return null;
    }

    /**
     * @param array $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return array
     */
    public function getTable()
    {
        return $this->table;
    }
}
