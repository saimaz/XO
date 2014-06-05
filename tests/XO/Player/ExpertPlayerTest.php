<?php


namespace tests\XO\Player;


use XO\Player\ExpertPlayer;

class ExpertPlayerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testExpertPlayerExists()
    {
        $expertPlayer = new ExpertPlayer();
    }
}
