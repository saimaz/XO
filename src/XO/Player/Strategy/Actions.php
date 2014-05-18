<?php


namespace XO\Player\Strategy;

class Actions extends Situation implements ActionsInterface
{
    public function defend()
    {
        return $this->getCoordinates();
    }

    public function attack()
    {
        return $this->attackCoordinates();
    }

    public function kill()
    {
        //killing is opposite to defend, so just invert symbol
        $this->invertSymbols(true);
        $move = $this->getCoordinates();

        //revert
        $this->invertSymbols(false);
        return $move;
    }

    public function random()
    {
        $x = rand(0, 2);
        $y = rand(0, 2);
        Utils::log('I was dumb random!');
        return array($x, $y);
    }

    protected function getCoordinates()
    {
        $action = $this->inverted ? 'Killing on' : 'Deffending on';

        $x = $this->defendLine('column');
        if (null !== $x) {
            $y = $this->getPossibleY($x);
            Utils::log($action . ' col');
            return array($y, $x);
        }

        $y = $this->defendLine('row');
        if (null !== $y) {
            $x = $this->getPossibleX($y);
            Utils::log($action . 'row!');
            return array($y, $x);
        }

        $defendCross = $this->defendCross();
        if ($this->isTurn($defendCross)) {
            Utils::log($action .' Cross!');
            return $defendCross;
        }
    }

    protected function defendCross()
    {
        $crossType = $this->getDefendedCross();

        if (isset($crossType)) {
            $cross = $this->getCrossLine($crossType);

            $x = $this->getEmptyField($cross);
            $y = $this->getCrossY($cross, $crossType);

            return [$x, $y];
        }
    }

    /**
     * @param $type
     *
     * @return int line index
     */
    protected function defendLine($type)
    {
        $count = 0;

        foreach ([0, 1, 2] as $index => $rowArray) {
            switch ($type) {
                case ('row'):
                    if (!$this->isFullRow($index)) {
                        $count = $this->countRow($index, 'enemy');
                    }
                    break;

                case ('column'):
                    if (!$this->isFullColumn($index)) {
                        $count = $this->countColumn($index, 'enemy');
                    }
                    break;
            }

            if ($count > 1) {
                return $index;
            }
        }
    }

    protected function getDefendedCross()
    {
        foreach ([true, false] as $crossRtl) {
            $cross = $this->getCrossLine($crossRtl);
            $count = $this->countBy($cross, 'enemy');

            if ($count > 1) {
                return $crossRtl;
            }
        }
    }

    private function attackCoordinates()
    {
        $attack = array(
            [0, 0],
            [1, 1]
        );
        foreach ($attack as $move) {
            list($x, $y) = $move;
            if ($this->isPossibleturn(array($x, $y))) {
                Utils::log("I atack!");
                return [$x, $y];
            }
        }
    }

    protected function isDefendable($line)
    {
        return ($this->countBy($line, 'enemy')) == 2;
    }

    protected function isAttackable($line)
    {
        return ($this->countBy($line, 'my')) == 2;
    }


}
