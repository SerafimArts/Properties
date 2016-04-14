<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 14:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Support;

/**
 * Class Registry
 * @package Serafim\Properties\Support
 */
class Registry
{
    /**
     * @var array|Parser[]
     */
    protected static $parsers = [];

    /**
     * @param $class
     * @return Parser
     */
    public static function get($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        
        if (!array_key_exists($class, static::$parsers)) {
            static::$parsers[$class] = new Parser($class);
        }

        return static::$parsers[$class];
    }

    /**
     * @return array|Parser[]
     */
    public static function getLoadedParsers()
    {
        return static::$parsers;
    }

    /**
     * @return int
     */
    public static function clear()
    {
        $count = count(static::$parsers);
        static::$parsers = [];
        return $count;
    }
}
