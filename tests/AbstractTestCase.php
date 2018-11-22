<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 14:49
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Tests;

use PHPUnit\Framework\TestCase;
use Predis\Client as Predis;
use Psr\SimpleCache\CacheInterface;
use Serafim\Properties\ArrayCache;
use Serafim\Properties\Bootstrap;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Simple\MemcachedCache;
use Symfony\Component\Cache\Simple\RedisCache;

/**
 * Class AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    protected function setUp()
    {
        Bootstrap::getInstance()->setCacheDriver($this->getCacheDriver());
    }

    protected function getCacheDriver(): CacheInterface
    {
        switch (getenv('APP_CACHE')) {
            case 'filesystem':
                return new FilesystemCache();
            case 'memcached':
                return new MemcachedCache($this->createMemcachedDriver());
            case 'redis':
                return new RedisCache(new Predis());
            case 'array':
            default:
                return new ArrayCache();
        }
    }

    protected function createMemcachedDriver(): \Memcached
    {
        $driver = new \Memcached();
        $driver->addServer(getenv('MEMCACHED_HOST') ?? 'localhost', getenv('MEMCACHED_PORT') ?? 11211);

        return $driver;
    }
}
