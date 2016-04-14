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
namespace Serafim\Properties\Unit;

use Serafim\Properties\Unit\Stub\ReadWrite;

class PropertiesTestCase extends AbstractTestCase
{
    public function testReadable()
    {
        $instance = new ReadWrite();
        $this->assertEquals('some', $instance->some);
    }

    public function testWritable()
    {
        $instance = new ReadWrite();
        $instance->any = 'new value';

        $this->assertEquals($instance->getWritablePropertyValue(), 'new value');

        $instance->setter = 'asdasd';

        $this->assertEquals($instance->getWritablePropertyValue(), 'asdasd');
    }

    public function testIsset()
    {
        $instance = new ReadWrite();

        $this->assertTrue(isset($instance->setter));
        $this->assertTrue(isset($instance->any));

        $this->assertTrue(isset($instance->some));
        $this->assertTrue(isset($instance->getter));
    }

    public function testUnset()
    {
        $instance = new ReadWrite();

        unset($instance->some, $instance->getter);

        $this->assertEquals('getter', $instance->getter);
        $this->assertEquals('some', $instance->some); // Can not delete write only property


        if ($this->canCatchPhpErrors()) {
            unset($instance->any);

            try {
                $instance->getWritablePropertyValue(); // Can not read deleted property
            } catch (\Throwable $e) {
                $this->assertInstanceOf(\LogicException::class, $e);
            }
        }
    }

    public function testDebugInfo()
    {
        if (version_compare('5.6', phpversion()) < 0) {
            $instance = new ReadWrite();

            ob_start();
            var_dump($instance);
            $content = ob_get_contents();
            ob_end_clean();

            $this->assertEquals(1, preg_match('/".*?"/isu', $content));
        }
    }
}
