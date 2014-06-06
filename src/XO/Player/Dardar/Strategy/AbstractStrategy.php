<?php


namespace XO\Player\Dardar\Strategy;

use XO\Player\Dardar\ActionsInterface;
use XO\Player\Dardar\Move\Move;
use XO\Player\Dardar\Move\MoveInterface;
use XO\Player\Dardar\Move\MoveNotFoundException;
use XO\Player\Dardar\Situation;

abstract class AbstractStrategy implements ActionsInterface, StrategyInterface
{
    protected $turn;

    protected $situation;

    protected $attack = array();

    public function __construct(Situation $situation)
    {
        $this->situation = $situation;
    }

    /**
     * Create map for action order
     * @return array
     */
    public function strategyActions()
    {
        return array(
            ActionsInterface::KILL,
            ActionsInterface::DEFEND,
            ActionsInterface::ATTACK,
            ActionsInterface::RANDOM
        );
    }

    public function getTurn()
    {
        foreach ($this->strategyActions() as $action) {
            try {
                return $this->$action()->getMove();
            } catch (MoveNotFoundException $e) {

            }
        }
        throw new \Exception('Turn is not generated');
    }

    /**
     * @return Move
     */
    public function defend()
    {
        return $this->situation->getDefendOrKillMove();
    }

    /**
     * Attacking is allways different
     * @return Move
     */
    abstract public function attack();

    public function kill()
    {
        //killing is opposite to defend, so just invert symbol
        $this->situation->invertSymbols(true);
        $move = $this->situation->getDefendOrKillMove();

        //revert
        $this->situation->invertSymbols(false);
        return $move;
    }

    /**
     * @return Move
     */
    public function random()
    {
        //possible moves returns inverted!
        list($y, $x) = $this->situation->randomMove();
        return new Move($x, $y);
    }

    public function isTurn($move)
    {
        return $this->situation->isTurn($move);
    }

    public function me()
    {
        return $this->situation->me();
    }

    public function he()
    {
        return $this->situation->he();
    }


    protected function addAttacks(array $attacks)
    {
        $this->setAttack(array_merge($this->getAttack(), $attacks));
    }

    protected function addAttack(MoveInterface $move)
    {
        $this->attack[] = $move;
    }

    /**
     * @param array $attack
     */
    public function setAttack($attack)
    {
        $this->attack = $attack;
    }

    /**
     * @return mixed
     */
    public function getAttack()
    {
        return $this->attack;
    }
}
