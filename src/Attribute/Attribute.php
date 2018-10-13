<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties\Attribute;

/**
 * Class Attribute
 */
class Attribute implements AttributeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $type;

    /**
     * @var Matchable
     */
    private $matcher;

    /**
     * Attribute constructor.
     * @param string $name
     * @param int $type
     */
    public function __construct(string $name, int $type = self::TYPE_UNDEFINED)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @param Matchable $hint
     * @return Matchable
     */
    public function addMatcher(Matchable $hint): Matchable
    {
        $this->matcher = $hint;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->type === static::TYPE_READABLE || $this->type === static::TYPE_PROPERTY;
    }

    /**
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->type === static::TYPE_WRITABLE || $this->type === static::TYPE_PROPERTY;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $this->matcher && $this->matcher->match($value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
