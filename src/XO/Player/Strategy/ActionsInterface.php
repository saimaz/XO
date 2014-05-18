<?php
namespace XO\Player\Strategy;

interface ActionsInterface
{
    public function defend();

    public function attack();

    public function kill();

    public function isTurn($move);
}
