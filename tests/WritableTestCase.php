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
namespace Serafim\Properties\Tests;
use Serafim\Properties\Exception\AccessDeniedException;
use Serafim\Properties\Tests\Stub\Writable;
use Serafim\Properties\Tests\Stub\WritableChild;

/**
 * Class WritableTestCase
 */
class WritableTestCase extends AbstractTestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPropertyReading(): void
    {
        $instance = new Writable();

        $instance->some = 23;

        $reflection = new \ReflectionObject($instance);
        $property = $reflection->getProperty('some');
        $property->setAccessible(true);


        $this->assertEquals($property->getValue($instance), 23);
    }

    /**
     * @return void
     */
    public function testPropertyNotReadable(): void
    {
        $this->expectException(AccessDeniedException::class);

        $instance = new Writable();
        $instance->some;
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testMethodWritable(): void
    {
        $instance = new Writable();
        $instance->any = 42;

        $this->assertEquals($instance->getAny(), 42);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testWritableFromChild(): void
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

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testChildOverloadingPropertiesBehaviors(): void
    {
        $instance = new WritableChild();

        $instance->any = 42;

        $this->assertEquals($instance->any, 42);
    }
}
