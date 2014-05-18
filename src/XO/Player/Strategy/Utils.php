<?php


namespace XO\Player\Strategy;
//use XO\Utilities\ChromePhp;

class Utils
{
    public static function log($message)
    {
        //echo $message . "\n";
        if (isset($_SERVER['REQUEST_URI'])) {
            //ChromePhp::log($message);
        }
    }
}
