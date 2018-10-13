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
use Serafim\Properties\Attribute\AttributeInterface;

/**
 * Class Bootstrap
 */
final class Bootstrap
{
    /**
     * @var Bootstrap
     */
    private static $instance;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var array|string[]
     */
    private $inheritance = [];

    /**
     * @return Bootstrap
     */
    public static function getInstance(): self
    {
        return self::$instance ?? self::setInstance(new self());
    }

    /**
     * @param self|Bootstrap $instance
     * @return Bootstrap
     */
    public static function setInstance(?self $instance): self
    {
        return self::$instance = $instance;
    }

    /**
     * Bootstrap constructor.
     */
    private function __construct()
    {
        $this->cache = new ArrayCache();
        $this->builder = new Builder();
    }

    /**
     * @return array
     */
    public function getInvocationPosition(): array
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS)[2] ?? [];

        return [$trace['file'] ?? __FILE__, $trace['line'] ?? __LINE__];
    }

    /**
     * @param null|CacheInterface $cache
     */
    public function setCacheDriver(?CacheInterface $cache): void
    {
        $this->cache = $cache ?? new ArrayCache();
    }

    /**
     * @param string $class
     * @param string $name
     * @return AttributeInterface|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getAttribute(string $class, string $name): ?AttributeInterface
    {
        foreach ($this->getClassInheritance($class) as $child) {
            $result = $this->getAttributeOrBuild($child, $name, function () use ($child) {
                foreach ($this->builder->buildClass($child) as $attribute) {
                    yield $this->getCacheKey($child, $attribute->getName()) => $attribute;
                }
            });

            if ($result) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param string $class
     * @return iterable|string[]
     */
    private function getClassInheritance(string $class): iterable
    {
        $inheritance = function() use ($class) {
            yield $class;
            yield from \array_keys(\class_parents($class));
            yield from \array_keys(\class_implements($class));
            yield from \array_keys(\class_uses($class));
        };

        if (! isset($this->inheritance[$class])) {
            $this->inheritance[$class] = \iterator_to_array($inheritance(), false);
        }

        return $this->inheritance[$class];
    }

    /**
     * @param string $class
     * @param string $name
     * @param \Closure $build
     * @return null|AttributeInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getAttributeOrBuild(string $class, string $name, \Closure $build): ?AttributeInterface
    {
        $key = $this->getCacheKey($class, $name);

        if (! $this->cache->has($key)) {
            foreach ($build() as $inner => $attribute) {
                $this->cache->set($inner, $attribute);
            }
        }

        return $this->cache->get($key);
    }

    /**
     * @param string $class
     * @param string $name
     * @return string
     */
    private function getCacheKey(string $class, string $name): string
    {
        return \implode(':', [$class, $name]);
    }
}
