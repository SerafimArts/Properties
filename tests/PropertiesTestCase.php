<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 17:19
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Tests;

use Serafim\Properties\Exception\AccessDeniedException;
use Serafim\Properties\Tests\Stub\ReadWrite;

/**
 * Class PropertiesTestCase
 */
class PropertiesTestCase extends AbstractTestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testReadable(): void
    {
        $instance = new ReadWrite();
        $this->assertEquals('some', $instance->some);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testWritable(): void
    {
        $instance = new ReadWrite();
        $instance->any = 'new value';

        $this->assertEquals($instance->getWritablePropertyValue(), 'new value');

        $instance->setter = 'asdasd';

        $this->assertEquals($instance->getWritablePropertyValue(), 'asdasd');
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIsset(): void
    {
        $instance = new ReadWrite();

        $this->assertTrue(isset($instance->setter));
        $this->assertTrue(isset($instance->any));

        $this->assertTrue(isset($instance->some));
        $this->assertTrue(isset($instance->getter));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testUnset(): void
    {
        $instance = new ReadWrite();

        unset($instance->some, $instance->getter);

        $this->assertEquals('getter', $instance->getter);
        $this->assertEquals('some', $instance->some); // Can not delete write only property

        unset($instance->any);

        try {
            $instance->getWritablePropertyValue(); // Can not read deleted property
        } catch (\Throwable $e) {
            $this->assertInstanceOf(AccessDeniedException::class, $e);
        }
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDebugInfo(): void
    {
        $instance = new ReadWrite();

        \ob_start();
        \var_dump($instance);
        $content = \ob_get_contents();
        \ob_end_clean();

        $this->assertEquals(1, \preg_match('/".*?"/isu', $content));
    }
}
