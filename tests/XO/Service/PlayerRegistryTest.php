<?php

namespace tests\XO\Service;

use XO\Player\DrunkPlayer;
use XO\Player\PlayerInterface;
use XO\Service\Game;
use XO\Service\PlayerRegistry;
use XO\Utilities\TableHelper;

/**
 * This class tests if registry players matches requirements
 */
class PlayerRegistryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function getData()
    {
        $out =  PlayerRegistry::getDefaultPlayers()->getNames();

        array_walk($out, function (&$name) {
            $name = [$name];
        });

        return $out;
    }

    /**
     * We expect correct first turn if player begins
     * @dataProvider getData
     * @param $name
     */
    public function testFirstTurn($name)
    {
        $player = PlayerRegistry::getDefaultPlayers()->get($name);
        $utility = new TableHelper();

        $this->assertEquals([1, 1], $player->turn($utility->createTable()));
    }

    /**
     * We expect every player to win against drunk player
     * @dataProvider getData
     * @param string $name
     */
    public function testWinAgainstDrunkPlayer($name)
    {
        if ($name == 'Drunk player') {
            return; // we do not want drunk to player to play against himself
        }

        $player = PlayerRegistry::getDefaultPlayers()->get($name);
        $utility = new TableHelper();
        $game = new Game($utility->createTable());
        $game->addPlayer($player);
        $game->addPlayer(new DrunkPlayer(), PlayerInterface::SYMBOL_O);

        for ($i = 0; $i < 4; $i++) {
            $game->getTurn();
        }

        $this->assertEquals(PlayerInterface::SYMBOL_X, $game->getWinner());
    }

    /**
     * We expect every player to use win possibility
     * @dataProvider getData
     * @param string $name
     */
    public function testUseWinPossibility($name)
    {
        if ($name == 'Drunk player') {
            return; // we do not expect that from drunk player ;)
        }

        $player = PlayerRegistry::getDefaultPlayers()->get($name);
        $table = [
            ['O', 'O', null],
            [null, 'X', null],
            ['X', null, null],
        ];
        $game = new Game($table);
        $game->addPlayer($player);

        $game->getTurn();

        $this->assertEquals(PlayerInterface::SYMBOL_X, $game->getWinner());
    }
}
