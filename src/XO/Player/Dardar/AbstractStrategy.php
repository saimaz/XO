<?php


namespace XO\Player\Dardar;


abstract class AbstractStrategy implements ActionsInterface
{

    protected $turn;


    protected $situation;

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
            $move = $this->$action();
            if ($this->situation->isTurn($move)) {
                return $move;
            }
        }

        throw new \Exception('Turn is not generated');
    }

    public function defend()
    {
        return $this->situation->getKillDefendCoordinates();
    }

    /**
     * Attacking is allways different
     * @return mixed
     */
    abstract public function attack();

    public function kill()
    {
        //killing is opposite to defend, so just invert symbol
        $this->situation->invertSymbols(true);
        $move = $this->situation->getKillDefendCoordinates();

        //revert
        $this->situation->invertSymbols(false);
        return $move;
    }

    public function random()
    {
        return $this->situation->randomMove();
    }

    /**
     * @param mixed $turn
     */
    public function setTurn($turn)
    {
        $this->situation->turn = $turn;
    }

    public function isTurn($move)
    {
        return $this->situation->isTurn($move);
    }

    public function me()
    {
        return $this->situation->mySymbol;
    }

    public function he()
    {
        return $this->situation->enemySymbol;
    }
}
