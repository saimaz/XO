<?php

namespace tests\XO\Injection;

use XO\Service\PlayerRegistry;

/**
 * This class tests if all players are injected into PlayerRegistry by default
 */
class PlayerInjectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function getTestInjectPlayersData()
    {
        $blacklist = [
            'XO\Player\PlayerInterface'
        ];
        $out = [];

        $reflection = new \ReflectionClass('XO\Player\PlayerInterface');
        $dir = dirname($reflection->getFileName());

        $files = scandir($dir);

        foreach ($files as $file) {
            if (strpos($file, '.php') !== false) {
                $class = 'XO\\Player\\'.str_replace('.php', '', $file);
                if (!in_array($class, $blacklist) && class_exists($class)) {
                    $out[] = [$class];
                }
            }
        }

        return $out;
    }

    /**
     * @dataProvider getTestInjectPlayersData
     * @param string $class
     */
    public function testInjectPlayer($class)
    {
        $registry = PlayerRegistry::getDefaultPlayers();
        $classes = [];

        foreach ($registry->getNames() as $name) {
            $classes[] = get_class($registry->get($name));
        }

        $this->assertTrue(in_array($class, $classes), sprintf('Class %s not registered in player registry', $class));
    }
}
