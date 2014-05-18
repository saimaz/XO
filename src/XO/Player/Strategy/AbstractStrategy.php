<?php


namespace XO\Player\Strategy;


class AbstractStrategy
{

    protected $turn;



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
