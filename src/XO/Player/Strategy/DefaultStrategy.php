<?php


namespace XO\Player\Strategy;


class DefaultStrategy extends AbstractStrategy
{

    protected $actions;

    public function __construct(ActionsInterface $actions)
    {
        $this->actions = $actions;
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
            $move = $this->actions->$action();
            if ($this->actions->isTurn($move)) {
                return $move;
            }
        }

        throw new \Exception('Turn is not generated');
    }
}
