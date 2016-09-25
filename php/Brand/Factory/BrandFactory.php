<?php
require_once(__DIR__ . "/../Type/Panda.php");
require_once(__DIR__ . "/../Type/Opteck.php");
require_once(__DIR__ . "/../Type/Others.php");
require_once(__DIR__ . "/../Type/Airsoft.php");
require_once(__DIR__ . "/../Type/Spotkey.php");
require_once(__DIR__ . "/../Type/Spotbdb.php");
require_once(__DIR__ . "/../Type/Spotusa.php");
require_once(__DIR__ . "/../Type/Spotrally.php");
require_once(__DIR__ . "/../Type/Tradologic.php");
require_once(__DIR__ . "/../Type/Marketpulse.php");
require_once(__DIR__ . "/../Type/Techfinancials.php");

/**
 * Get instance of specific brand
 *
 */
class BrandFactory
{
    public static function getInstance($brand)
    {
        $class = self::getClassName($brand);
        return new $class();
    }
    
    public static function getClassName($brand)
    {
        $brand = ucwords(strtolower(str_replace(array(' ', '-'), '', $brand)));
        return (class_exists($brand)) ? $brand : 'Others';
    }
}
