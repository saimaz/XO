<?php

namespace tests\XO\Utilities;

use XO\Utilities\TableHelper;

class TableHelperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test if empty table creation works
     */
    public function testCreateEmptyTable()
    {
        $utility = new TableHelper();

        $this->assertEquals([[null, null, null], [null, null, null], [null, null, null]], $utility->createTable());
    }

    /**
     * @return array
     */
    public function getTestGetRowData()
    {
        $table1 = [
            [ null, 'X', null ],
            [ 'O', 'X', null ],
            [ null, 'O', 'X' ],
        ];
        $out = [];

        // case #0 table 1, row 0
        $out[] = [$table1, 0, [ null, 'X', null ]];

        // case #1 table 1, row 1
        $out[] = [$table1, 1, [ 'O', 'X', null ]];

        // case #2 table 1, row 2
        $out[] = [$table1, 2, [ null, 'O', 'X' ]];

        return $out;
    }

    /**
     * Test if we are able to get correct row
     * @dataProvider getTestGetRowData()
     * @param array $table
     * @param int $row
     * @param array $expected
     */
    public function testGetRow($table, $row, $expected)
    {
        $utility = new TableHelper($table);

        $this->assertEquals($utility->getRow($row), $expected);
    }

    /**
     * @return array
     */
    public function getTestGetColumnData()
    {
        $table1 = [
            [ null, 'X', null ],
            [ 'O', 'X', null ],
            [ null, 'O', 'X' ],
        ];
        $out = [];

        // case #0 table 1, row 0
        $out[] = [$table1, 0, [ null, 'O', null ]];

        // case #1 table 1, row 1
        $out[] = [$table1, 1, [ 'X', 'X', 'O' ]];

        // case #2 table 1, row 2
        $out[] = [$table1, 2, [ null, null, 'X' ]];

        return $out;
    }

    /**
     * Test if we are able to get correct column
     * @dataProvider getTestGetColumnData()
     * @param array $table
     * @param int $column
     * @param array $expected
     */
    public function testGetColumn($table, $column, $expected)
    {
        $utility = new TableHelper($table);

        $this->assertEquals($utility->getColumn($column), $expected);
    }

    /**
     * @return array
     */
    public function getTestGetCrossData()
    {
        $table1 = [
            [ null, 'X', null ],
            [ 'O', 'X', null ],
            [ null, 'O', 'X' ],
        ];
        $out = [];

        // case #0 table 1, ltr
        $out[] = [$table1, false, [ null, 'X', 'X' ]];

        // case #1 table 1, rtl
        $out[] = [$table1, true, [ null, 'X', null ]];

        return $out;
    }

    /**
     * Test if we are able to get correct cross line
     * @dataProvider getTestGetCrossData()
     * @param array $table
     * @param bool $rtl
     * @param array $expected
     */
    public function testGetCross($table, $rtl, $expected)
    {
        $utility = new TableHelper($table);

        $this->assertEquals($utility->getCross($rtl), $expected);
    }
}
