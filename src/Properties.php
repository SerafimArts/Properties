<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 17:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties;

use Serafim\Properties\Support\Registry;
use Serafim\Properties\Support\Strings as Str;

/**
 * Class Properties
 * @package Serafim\Properties
 */
trait Properties
{
    use Getters, Setters;

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        // Try to call `getProperty` method
        $getter = Str::getGetter($name);
        if (method_exists($this, $getter)) {
            return true;
        }

        return Registry::get($this)->has($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __unset($name)
    {
        $registry = Registry::get($this);

        $reflection = new \ReflectionClass($this);

        if ($registry->has($name) && $reflection->hasProperty($name)) {
            $property = $reflection->getProperty($name);

            if ($property->isProtected() || $property->isPublic()) {
                unset($this->$name);
                return true;
            }

        }

        return false;
    }
}
