<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 13:11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Support;

/**
 * Class Strings
 * @package Serafim\Properties\Support
 */
class Strings
{
    const PREFIX_GETTER = 'get';
    const PREFIX_BOOL_GETTER = 'is';
    const PREFIX_SETTER = 'set';

    /**
     * The cache of camel-cased words.
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * Convert a value to studly caps case.
     *
     * @param string $word
     * @param array|string[] $dividers
     * @return string
     */
    public static function studly($word, array $dividers = ['-', '_'])
    {
        $key = $word;

        if (!array_key_exists($key, static::$studlyCache)) {
            $word = ucwords(str_replace($dividers, ' ', $word));
            static::$studlyCache[$key] = str_replace(' ', '', $word);
        }

        return static::$studlyCache[$key];
    }

    /**
     * Convert a value to camel case.
     *
     * @param string $word
     * @param array|string[] $dividers
     * @return string
     */
    public static function camel($word, array $dividers = ['-', '_'])
    {
        if (!array_key_exists($word, static::$camelCache)) {
            static::$camelCache[$word] = lcfirst(static::studly($word, $dividers));
        }

        return static::$camelCache[$word];
    }

    /**
     * @param string $field
     * @return string
     */
    public static function getGetter($field)
    {
        return static::PREFIX_GETTER . static::camel($field);
    }

    /**
     * @param string $field
     * @return string
     */
    public static function getBooleanGetter($field)
    {
        return static::PREFIX_BOOL_GETTER . static::camel($field);
    }

    /**
     * @param string $field
     * @return string
     */
    public static function getSetter($field)
    {
        return static::PREFIX_SETTER . static::camel($field);
    }
}
