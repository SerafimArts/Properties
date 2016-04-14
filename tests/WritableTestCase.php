<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 14:53
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Unit;
use Serafim\Properties\Unit\Stub\Writable;
use Serafim\Properties\Unit\Stub\WritableChild;

/**
 * Class WritableTestCase
 * @package Serafim\Properties\Unit
 */
class WritableTestCase extends AbstractTestCase
{
    public function testPropertyReading()
    {
        $instance = new Writable();

        $instance->some = 23;

        $reflection = new \ReflectionObject($instance);
        $property = $reflection->getProperty('some');
        $property->setAccessible(true);


        $this->assertEquals($property->getValue($instance), 23);
    }

    public function testPropertyNotReadable()
    {
        if ($this->canCatchPhpErrors()) {
            $instance = new Writable();
            $hasErrors = false;

            try {
                $instance->some;
                $hasErrors = true;
            } catch (\Throwable $e) {
                $this->assertInstanceOf(\Error::class, $e);
            }

            if ($hasErrors) {
                $this->throwException(new \LogicException('Failed asserting'));
            }
        }
    }

    public function testMethodWritable()
    {
        $instance = new Writable();
        $instance->any = 42;

        $this->assertEquals($instance->getAny(), 42);
    }

    public function testWritableFromChild()
    {
        $instance = new WritableChild();

        $instance->some = 23;
        $instance->any = 42;

        $reflection = new \ReflectionObject($instance);
        $property = $reflection->getProperty('some');
        $property->setAccessible(true);

        $this->assertEquals($property->getValue($instance), 23);
        $this->assertEquals($instance->getAny(), 42);
    }

    public function testChildOverloadingPropertiesBehaviors()
    {
        $instance = new WritableChild();

        $instance->any = 42;

        $this->assertEquals($instance->any, 42);
    }
}
