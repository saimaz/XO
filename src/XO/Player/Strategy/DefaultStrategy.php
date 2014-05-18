<?php


namespace XO\Player\Strategy;


class DefaultStrategy extends AbstractStrategy
{

    protected $actions;

    public function __construct(ActionsInterface $actions)
    {
        $this->actions = $actions;
    }

    public function strategyActions()
    {
        return array(
            parent::KILL,
            parent::DEFEND,
            parent::ATTACK,
            parent::RANDOM
        );
    }

    public function getTurn()
    {
        foreach ($this->strategyActions() as $action) {
            $move = $this->actions->$action();
            if ($this->actions->isTurn($move)) {
                return $move;
            }
        }

        throw new \Exception('Turn is not generated');
    }
}
