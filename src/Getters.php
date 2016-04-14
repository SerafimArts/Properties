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
namespace Serafim\Properties;

use Serafim\Properties\Support\Registry;
use Serafim\Properties\Support\Strings as Str;

/**
 * Class Getters
 * @package Serafim\Properties
 */
trait Getters
{
    /**
     * @param string $name
     * @return mixed|void
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function __get($name)
    {
        // Try to call `getProperty` method
        $getter = Str::getGetter($name);
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }


        $parser = Registry::get($this);

        if ($parser->has($name)) {
            $property  = $parser->get($name);
            $isBoolean = $property->typeOf('bool') || $property->typeOf('boolean');
            $getter    = Str::getBooleanGetter($name);

            // If boolean - try to call `isProperty`
            if ($isBoolean && method_exists($this, $getter)) {
                return $this->{$getter}();
            }

            if ($parser->isReadable($name)) {
                // Return declared value
                return $this->getPropertyValue($name);
            }

            $exception = sprintf('Can not read write only property %s::$%s', get_class($this), $name);
            throw new \LogicException($exception);
        }
    }

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
     * @param string $name
     * @return mixed
     */
    private function getPropertyValue($name)
    {
        $reflection = new \ReflectionProperty($this, $name);
        $reflection->setAccessible(true);
        return $reflection->getValue($this);
    }
}
