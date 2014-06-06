<?php

namespace XO\Player\Dardar;

class Utils
{
    public static function log($message)
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            //ChromePhp::log($message);
        }
    }
}
