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
 * Class Setters
 * @package Serafim\Properties
 */
trait Setters
{
    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function __set($name, $value)
    {
        // Try to call `setProperty` method
        $setter = Str::getSetter($name);
        if (method_exists($this, $setter)) {
            return $this->{$setter}($value);
        }

        $parser = Registry::get($this);
        if ($parser->has($name)) {
            if (!$parser->isWritable($name)) {
                throw new \LogicException(sprintf('Can not set value to read only property %s::$%s', get_class($this), $name));
            }
            
            if (!$parser->typesAreEqual($name, $value)) {
                throw new \LogicException(sprintf('Can not set value: types mismatch %s::$%s', get_class($this), $name));
            }

            $this->setPropertyValue($name, $value);
        }

        $this->$name = $value;
    }

    /**
     * @param $name
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function __unset($name)
    {
        $registry = Registry::get($this);

        $reflection = new \ReflectionClass($this);

        $isWritableProperty = $registry->has($name) && $registry->isWritable($name) && $reflection->hasProperty($name);

        if ($isWritableProperty) {
            $property = $reflection->getProperty($name);

            if ($property->isProtected() || $property->isPublic()) {
                unset($this->$name);
                return true;
            }

        }

        return false;
    }

    /**
     * @param string $name
     * @param $value
     * @return void
     */
    private function setPropertyValue($name, $value)
    {
        $reflection = new \ReflectionProperty($this, $name);
        $reflection->setAccessible(true);
        $reflection->setValue($this, $value);
    }
}
