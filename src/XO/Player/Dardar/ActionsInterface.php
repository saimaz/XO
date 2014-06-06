<?php
namespace XO\Player\Dardar;

interface ActionsInterface
{
    const KILL = 'kill';

    const ATTACK = 'attack';

    const DEFEND = 'defend';

    const RANDOM = 'random';

    public function defend();

    public function attack();

    public function kill();

    public function isTurn($move);
}
