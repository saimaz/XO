<?php


namespace XO\Player\Dardar\Move;

class NullMove implements MoveInterface
{
    public function getMove()
    {
        throw new MoveNotFoundException('This move is invalid.');
    }
}
