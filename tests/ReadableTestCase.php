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
use Serafim\Properties\Tests\Stub\Readable;
use Serafim\Properties\Tests\Stub\ReadableChild;

/**
 * Class ReadableTestCase
 */
class ReadableTestCase extends AbstractTestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPropertyReadable(): void
    {
        $instance = new Readable();
        $this->assertEquals('property', $instance->some);
    }

    /**
     * @return void
     */
    public function testPropertyNotWritable(): void
    {
        $this->expectException(AccessDeniedException::class);

        $instance = new Readable();
        $instance->some = 'new value';
    }

    /**
     * @return void
     */
    public function testMethodNotWritable(): void
    {
        $this->expectException(AccessDeniedException::class);

        $instance = new Readable();
        $instance->any = 'new value';
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testMethodReadable(): void
    {
        $instance = new Readable();

        $this->assertEquals($instance->getAny(), $instance->any);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testMethodBooleanReadable(): void
    {
        $instance = new Readable();

        $this->assertEquals($instance->isBoolMethod(), $instance->boolMethod);
        $this->assertEquals($instance->isBoolMethod(), $instance->bool_method);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testReadingFromChild(): void
    {
        $instance = new ReadableChild();

        $this->assertEquals('property', $instance->some);
        $this->assertEquals($instance->getAny(), $instance->any);
        $this->assertEquals($instance->isBoolMethod(), $instance->boolMethod);
        $this->assertEquals($instance->isBoolMethod(), $instance->bool_method);
    }
}
