<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties;

use Psr\SimpleCache\CacheInterface;

/**
 * Class ArrayCache
 */
class ArrayCache implements CacheInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = null): bool
    {
        $this->data[$key] = $value;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function delete($key): bool
    {
        if ($this->has($key)) {
            unset($this->data[$key]);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function clear(): bool
    {
        $this->data = [];

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getMultiple($keys, $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $this->get($key, $default);
        }
    }

    /**
     * @inheritdoc
     */
    public function setMultiple($values, $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function has($key): bool
    {
        return isset($this->data[$key]) || \array_key_exists($key, $this->data);
    }
}
