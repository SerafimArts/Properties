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
     * @return array
     */
    public function __debugInfo()
    {
        $result = [];
        $reflection = new \ReflectionObject($this);
        $registry = Registry::get($this);

        foreach ($reflection->getProperties() as $property) {
            $name = $property->name;
            if ($property->isStatic()) {
                continue;
            }

            if ($property->isPublic()) {
                $result[$name] = $this->$name;
            }

            if ($property->isProtected() || $property->isPrivate()) {
                $property->setAccessible(true);

                if ($registry->has($name) && $registry->isReadable($name)) {
                    $result[$name] = $property->getValue($this);
                }
            }
        }

        return $result;
    }
}
