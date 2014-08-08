<?php


namespace XO\Player\Dardar\Specials;

use XO\Player\Dardar\Situation;

class AbstractSpecial
{
    /**
     * @var Situation
     */
    public $situation;

    public $skipped;

    /**
     * @param \XO\Player\Dardar\Situation $situation
     * @return $this;
     */
    public function setSituation(Situation $situation)
    {
        $this->situation = $situation;
        return $this;
    }

    public function setSkipped($skipped)
    {
        $this->skipped = $skipped;
        return $this;
    }

    public function isSkipped()
    {
        return $this->skipped >= rand(1, 100);
    }

    protected function isFirstNotSkipped()
    {
        return $this->situation->countMoves() == 0 && !$this->isSkipped();
    }
}