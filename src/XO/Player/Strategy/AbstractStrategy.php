<?php


namespace XO\Player\Strategy;


class AbstractStrategy {

    protected $turn;

    const KILL = 'kill';

    const ATTACK = 'attack';

    const DEFEND = 'defend';

    const RANDOM = 'random';

    /**
     * @param mixed $turn
     */
    public function setTurn($turn)
    {
        $this->turn = $turn;
    }

    /**
     * @return mixed
     */
    public function getTurn()
    {
        return $this->turn;
    }

}
