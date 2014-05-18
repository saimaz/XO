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
     * We expect every player to win against drunk player
     * @dataProvider getData
     * @param string $name
     */
    public function testWinAgainstDrunkPlayer($name)
    {
        if ($name == 'drunk') {
            return; // we do not want drunk to player to play against himself
        }

        $player = PlayerRegistry::getDefaultPlayers()->get($name);
        $utility = new TableHelper();
        $game = new Game($utility->createTable());
        $game->addPlayer($player);
        $game->addPlayer(new DrunkPlayer(), PlayerInterface::SYMBOL_O);

        $this->assertEquals(PlayerInterface::SYMBOL_X, $game->autoPlay());
    }

    /**
     * @return array
     */
    public function getTestUseWinPossibilityData()
    {
        $tables = [];

        $tables[] = [
            [PlayerInterface::SYMBOL_O, PlayerInterface::SYMBOL_O, null],
            [null, PlayerInterface::SYMBOL_X, null],
            [PlayerInterface::SYMBOL_X, null, null],
        ];

        $tables[] = [
            [PlayerInterface::SYMBOL_O, PlayerInterface::SYMBOL_O, null],
            [PlayerInterface::SYMBOL_X, PlayerInterface::SYMBOL_X, null],
            [null, null, null],
        ];

        $tables[] = [
            [PlayerInterface::SYMBOL_O, PlayerInterface::SYMBOL_O, null],
            [null, PlayerInterface::SYMBOL_X, null],
            [PlayerInterface::SYMBOL_X, PlayerInterface::SYMBOL_O, PlayerInterface::SYMBOL_X],
        ];

        $tables[] = [
            [PlayerInterface::SYMBOL_O, null, PlayerInterface::SYMBOL_O],
            [null, PlayerInterface::SYMBOL_O, null],
            [PlayerInterface::SYMBOL_X, null, PlayerInterface::SYMBOL_X],
        ];

        $out = [];

        foreach ($tables as $table) {
            foreach (PlayerRegistry::getDefaultPlayers()->getNames() as $name) {
                $out[] = [$name, $table];
            }
        }

        return $out;
    }

    /**
     * We expect every player to use win possibility
     * @dataProvider getTestUseWinPossibilityData
     * @param string $name
     * @param $table
     */
    public function testUseWinPossibility($name, $table)
    {
        if ($name == 'drunk') {
            return; // we do not expect that from drunk player ;)
        }

        $player = PlayerRegistry::getDefaultPlayers()->get($name);
        $game = new Game($table);
        $game->addPlayer($player);

        $game->getTurn();

        $this->assertEquals(PlayerInterface::SYMBOL_X, $game->getWinner());
    }
}
