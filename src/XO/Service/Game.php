<?php

namespace XO\Service;

#########################################################################################################
#        Table Game XO
#
#   Here's the board. Each square is marked as coordinates which must understand XO robot.
# When someone makes turn, robot returns number with his turn. Of course robot should always win ;).
# f.e. O is defined as [1,3] = O array and X is [2,2] = X.
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
use XO\Utilities\TableHelper;

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
     * @var TableHelper
     */
    protected $tableHelper;

    /**
     * @var array
     */
    protected $players = [];

    /**
     * @param array $table
     */
    public function __construct($table = [])
    {
        if (sizeof($table) != 0) {
            $this->table = $table;
        } else {
            $this->table = array_fill(0, 3, array_fill(0, 3, null));
        }
    }

    /**
     * @param PlayerInterface $player
     * @param string $symbol
     */
    public function addPlayer(PlayerInterface $player, $symbol = PlayerInterface::SYMBOL_X)
    {
        $this->players[$symbol] = $player;
    }

    /**
     * Executes turns by added players
     *
     * @return array
     */
    public function getTurn()
    {
        /** @var PlayerInterface $player */
        foreach ($this->players as $symbol => $player) {
            if ($this->getWinner() === null) {
                $turn = $player->turn($this->table);
                $this->doTurn($turn, $symbol);
            }
        }

        return $this->table;
    }

    /**
     * Sets turn
     *
     * @param array $turn
     * @param string $symbol
     */
    public function doTurn($turn, $symbol = PlayerInterface::SYMBOL_X)
    {
        if ($this->table[$turn[0]][$turn[1]] === null) {
            $this->table[$turn[0]][$turn[1]] = $symbol;
        } else {
            throw new \Exception("Field is already filled");
        }

        return $this->table;
    }

    /**
     * Returns symbol of winner in case we have a winner
     * @return null|string
     */
    public function getWinner()
    {
        $winner = null;
        $tableHelper = new TableHelper($this->table);
        $result = [];
        foreach ([0, 1, 2] as $row) {
            $result[] = $tableHelper->getRow($row);
            $result[] = $tableHelper->getColumn($row);
        }
        $result[] = $tableHelper->getCross();
        $result[] = $tableHelper->getCross(true);
        foreach ($result as $case) {
            if ($case[0] !== null && count(array_unique($case)) == 1) {
                $winner = $case[0];
                return $winner;
            }
        }
        return $winner;
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
