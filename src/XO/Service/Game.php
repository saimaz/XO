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

Class Game
{
    protected $map;

    public function __construct()
    {
        $this->map = [];
    }
}
