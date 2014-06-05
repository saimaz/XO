<?php

namespace XO\Service;

use XO\Player\AlfPlayer;
use XO\Player\DrunkPlayer;
use XO\Player\ExpertPlayer;
use XO\Player\PlayerInterface;

/**
 * This class registers players into registry
 */
class PlayerRegistry
{

    /**
     * @var array
     */
    protected $container = [];

    /**
     * @param string $name
     * @param PlayerInterface $player
     */
    public function setPlayer($name, PlayerInterface $player)
    {
        $this->container[$name] = $player;
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return array_keys($this->container);
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     * @return PlayerInterface
     */
    public function get($name)
    {
        if (!isset($this->container)) {
            throw new \InvalidArgumentException(sprintf(
                'Player %s not registered. Players: %s',
                $name,
                join(',', $this->getNames())
            ));
        }
        return $this->container[$name];
    }

    /**
     * Returns registry with default players
     * @return PlayerRegistry
     */
    public static function getDefaultPlayers()
    {
        $instance = new self;

        $instance->setPlayer('Drunk player', new DrunkPlayer());
        $instance->setPlayer('Alf player', new AlfPlayer());
        $instance->setPlayer('Expert player', new ExpertPlayer());

        return $instance;
    }
}
