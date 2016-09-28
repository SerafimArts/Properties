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

use Serafim\Properties\Exception\AccessDeniedException;
use Serafim\Properties\Unit\Stub\Readable;
use Serafim\Properties\Unit\Stub\ReadableChild;

/**
 * Class ReadableTestCase
 * @package Serafim\Properties\Unit
 */
class ReadableTestCase extends AbstractTestCase
{
    public function testPropertyReadable()
    {
        $instance = new Readable();
        $this->assertEquals('property', $instance->some);
    }

    public function testPropertyNotWritable()
    {
        if ($this->canCatchPhpErrors()) {
            $instance = new Readable();
            $hasErrors = false;

            try {
                $instance->some = 'new value';
                $hasErrors = true;
            } catch (\Throwable $e) {
                $this->assertInstanceOf(AccessDeniedException::class, $e);
            }

            if ($hasErrors) {
                $this->throwException(new \LogicException('Failed asserting'));
            }
        }
    }

    public function testMethodNotWritable()
    {
        if ($this->canCatchPhpErrors()) {
            $instance = new Readable();
            $hasErrors = false;

            try {
                $instance->any = 'new value';
                $hasErrors = true;
            } catch (\Throwable $e) {
                $this->assertInstanceOf(AccessDeniedException::class, $e);
            }

            if ($hasErrors) {
                $this->throwException(new \LogicException('Failed asserting'));
            }
        }
    }

    public function testMethodReadable()
    {
        $instance = new Readable();
        $this->assertEquals($instance->getAny(), $instance->any);
    }

    public function testMethodBooleanReadable()
    {
        $instance = new Readable();
        $this->assertEquals($instance->isBoolMethod(), $instance->boolMethod);
        $this->assertEquals($instance->isBoolMethod(), $instance->bool_method);
    }

    public function testReadingFromChild()
    {
        $instance = new ReadableChild();
        $this->assertEquals('property', $instance->some);
        $this->assertEquals($instance->getAny(), $instance->any);
        $this->assertEquals($instance->isBoolMethod(), $instance->boolMethod);
        $this->assertEquals($instance->isBoolMethod(), $instance->bool_method);
    }
}
