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
use Serafim\Properties\Exception\AccessDeniedException;

/**
 * Class Properties
 * @package Serafim\Properties
 */
trait Properties
{
    /**
     * @param string $name
     * @return mixed|null
     * @throws AccessDeniedException|\InvalidArgumentException|\LogicException
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
            $property = $parser->get($name);
            $isBoolean = $property->typeOf('bool') || $property->typeOf('boolean');
            $getter = Str::getBooleanGetter($name);

            // If boolean - try to call `isProperty`
            if ($isBoolean && method_exists($this, $getter)) {
                return $this->{$getter}();
            }

            if ($parser->isReadable($name)) {
                // Return declared value
                return $this->getPropertyValue($name);
            }

            $exception = sprintf('Can not read write-only property %s::$%s', get_class($this), $name);
            throw new AccessDeniedException($exception);
        }

        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws AccessDeniedException|\InvalidArgumentException|\TypeError
     */
    public function __set($name, $value)
    {
        // Try to call `setProperty` method
        $setter = Str::getSetter($name);
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
            return;
        }


        $parser = Registry::get($this);
        if ($parser->has($name)) {
            if (!$parser->isWritable($name)) {
                $exception = sprintf('Can not set value to read-only property %s::$%s', get_class($this), $name);
                throw new AccessDeniedException($exception);
            }

            if (!$parser->typesAreEqual($name, $value)) {
                $exception = sprintf(
                    'Value for property %s::%s must be of the type %s, %s given',
                    get_class($this),
                    $name,
                    $parser->get($name)->getAvailableTypes(),
                    gettype($value)
                );
                throw new \TypeError($exception);
            }

            $this->setPropertyValue($name, $value);
        }

        $this->$name = $value;
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
}
