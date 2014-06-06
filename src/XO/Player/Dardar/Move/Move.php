<?php

namespace XO\Player\Dardar\Move;

class Move implements MoveInterface
{
    protected $x;

    protected $y;

    public function __construct($y, $x)
    {
        $this->setX($x);
        $this->setY($y);
    }

    /**
     * @return array
     */
    public function getMove()
    {
        return array($this->x, $this->y);
    }

    /**
     * Returns a natural array with not swapped values
     * @return array
     */
    public function getNaturalMove()
    {
        return array($this->y, $this->x);
    }

    /**
     * @param mixed $x
     *
     * @throws \InvalidArgumentException
     */
    public function setX($x)
    {
        if (!is_int($x)) {
            throw new \InvalidArgumentException('X cannot be set! ' . $x);
        }
        $this->x = $x;
    }


    /**
     * @param $y
     *
     * @throws \InvalidArgumentException
     */
    public function setY($y)
    {
        if (!is_int($y)) {
            throw new \InvalidArgumentException('Y cannot be set! ' . $y);
        }
        $this->y = $y;
    }
}
