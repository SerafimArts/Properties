<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 15:24
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Unit;

use Serafim\Properties\Support\Registry;
use Serafim\Properties\Unit\Stub\Readable;
use Serafim\Properties\Unit\Stub\Writable;

/**
 * Class MemoryTestCase
 * @package Serafim\Properties\Unit
 */
class MemoryTestCase extends AbstractTestCase
{
    /**
     * @return array|Readable[]|Writable[]
     */
    private function getMocks()
    {
        return [
            new Readable(), new Writable(),
            new Readable(), new Writable(),
            new Readable(), new Writable(),
            new Readable(), new Writable(),
            new Readable(), new Writable(),
        ];
    }

    /**
     * @return array|Stub\Readable[]|Stub\Writable[]
     */
    private function getBootedMocks()
    {
        $instances = $this->getMocks();
        foreach ($instances as $instance) {
            if ($instance instanceof Readable) {
                $value = $instance->some;
            } else {
                $instance->some = mt_rand(0, 9999);
            }
        }

        return $instances;
    }

    public function testLazyLoad()
    {
        $this->getMocks();

        $this->assertCount(0, Registry::getLoadedParsers());
    }

    public function testMemoryOptimization()
    {
        $this->getBootedMocks();

        $this->assertCount(2, Registry::getLoadedParsers());
    }

    public function testRegistryClearing()
    {
        $this->getBootedMocks();

        $this->assertCount(2, Registry::getLoadedParsers());

        Registry::clear();

        $this->assertCount(0, Registry::getLoadedParsers());
    }

    public function testMemory()
    {
        $after = null;

        for ($i = 0; $i < 10; $i++) {
            $before = memory_get_usage(true);

            $this->getBootedMocks();

            Registry::clear();

            if ($after === null) {
                $before = memory_get_usage(true);
            }

            $after = memory_get_usage(true);

            $this->assertEquals($before, $after);
        }
    }

    public function testMemoryMinimalUsage()
    {
        if (version_compare('7.0', phpversion()) < 0) {
            gc_mem_caches();
        }

        gc_collect_cycles();
        $before = memory_get_usage(true);

        $this->getBootedMocks();
        gc_collect_cycles();

        $this->assertEquals($before, memory_get_usage(true));
    }
}
