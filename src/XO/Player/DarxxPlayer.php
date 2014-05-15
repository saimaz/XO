<?php

namespace XO\Player;

/**
 * This class provides not very smart player ;)
 */
class DarxxPlayer implements PlayerInterface
{
    /**
     * @inheritdoc
     */
    private $a,$b,$c,$table;
    public function check($l,$x,$symbol) {
        if ($x === ',,' || $x === ",$symbol,$symbol") {
            return [$l,0];
        }
        if ($x === "$symbol,," || $x === "$symbol,,$symbol") {
            return [$l,1];
        }

        if ($x === "$symbol,$symbol," || $x === ",$symbol,") {
            return [$l,2];
        }

        return false;
    }
    public function ninja($l,$symbol) {
        if ($this->table[1][$l] == $symbol && $this->table[2][$l] == $symbol && !isset($this->table[0][$l])) {
            return [0,$l];
        }
        if ($this->table[1][$l] == $symbol && !isset($this->table[2][$l]) == $symbol && $this->table[0][$l] == $symbol) {
            return [2,$l];
        }
        if (!isset($this->table[1][$l]) == $symbol && $this->table[2][$l] == $symbol && $this->table[0][$l] == $symbol) {
            return [1,$l];
        }
    }

    public function row($symbol) {
        $aCheck = $this->check(0,$this->a,$symbol);
        $bCheck = $this->check(1,$this->b,$symbol);
        $cCheck = $this->check(2,$this->c,$symbol);
        if (!empty($aCheck)) {
            return $aCheck;
        }
        if (!empty($bCheck)) {
            return $bCheck;
        }
        if (!empty($cCheck)) {
            return $cCheck;
        }
        return false;
    }

    public function turn($table, $symbol = self::SYMBOL_X)
    {
        $this->table = $table;
        $this->a = join(',',$this->table[0]);
        $this->b = join(',',$this->table[1]);
        $this->c = join(',',$this->table[2]);

        $mano = $this->row($symbol);
        if ($mano) {
            return $mano;
        }
        else {
            $mano = $this->ninja(1,$symbol);
            if ($mano) {
                return $mano;
            }
            $mano = $this->ninja(0,$symbol);
            if ($mano) {
                return $mano;
            }
            $mano = $this->ninja(2,$symbol);
            if ($mano) {
                return $mano;
            }
        }
        $x = $y = 0;
        while ($table[$x][$y] !== null) {
            $x = rand(0, 2);
            $y = rand(0, 2);
        }

        return [$x, $y];
    }
}
