<?php

namespace XO\Utilities;

/**
 * This class provides some basic tools to handle game table
 */
class TableHelper
{

    /**
     * @var array
     */
    protected $table;

    /**
     * @param null|array $table
     */
    public function __construct($table = null)
    {
        $this->table = $table ? $table : $this->createTable();
    }

    /**
     * creates empty game table
     */
    public function createTable()
    {
        return array_fill(0, 3, array_fill(0, 3, null));
    }

    /**
     * @param int $index
     * @return array
     */
    public function getRow($index)
    {
        return $this->table[$index];
    }

    /**
     * @param int $index
     * @return array
     */
    public function getColumn($index)
    {
        $table = $this->table;
        $callback = function (&$value) use ($index) {
            $value = $value[$index];
        };
        array_walk($table, $callback);
        return $table;
    }

    /**
     * @param bool $rtl
     * @return array
     */
    public function getCross($rtl = false)
    {
        $table = $this->table;
        $index = 0;
        $callback = function (&$value) use (&$index, $rtl) {
            $value = $value[$rtl ? (2 - $index) : $index];
            $index++;
        };
        array_walk($table, $callback);
        return $table;
    }

    /**
     * @return array
     */
    public function getPossibleMoves()
    {
        $out = [];

        foreach ($this->table as $row => $data) {
            foreach ($data as $col => $value) {
                if ($value === null) {
                    $out[] = [$row, $col];
                }
            }
        }

        return $out;
    }
}
