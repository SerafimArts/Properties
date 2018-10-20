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
     * @var string
     */
    private $camel;

    /**
     * Attribute constructor.
     * @param string $name
     * @param int $type
     */
    public function __construct(string $name, int $type = self::TYPE_UNDEFINED)
    {
        $this->name  = $name;
        $this->type  = $type;
        $this->camel = $this->toCamelCase($name);
    }

    /**
     * @param string $name
     * @return string
     */
    private function toCamelCase(string $name): string
    {
        $name = \ucwords($name, '_');
        $name = \str_replace('_', '', $name);

        return $name;
    }

    /**
     * @param mixed|object $context
     * @return mixed
     */
    public function getValueFrom($context)
    {
        $value = $this->getFromAttribute($context);

        switch (true) {
            case \method_exists($context, $getter = $this->getPropertyGetter()):
                return $this->getUsingGetter($context, $getter, $value);

            case \method_exists($context, $getter = $this->getBooleanGetter()):
                return $this->getUsingGetter($context, $getter, $value);

            default:
                return $value;
        }
    }

    /**
     * @param mixed $context
     * @return mixed
     */
    private function getFromAttribute($context)
    {
        return (function (string $name) {
            return \property_exists($this, $name) ? $this->$name : null;
        })->call($context, $this->name);
    }

    /**
     * @return string
     */
    private function getPropertyGetter(): string
    {
        return 'get' . $this->camel;
    }

    /**
     * @param object $context
     * @param string $getter
     * @param mixed $value
     * @return mixed
     */
    private function getUsingGetter($context, string $getter, $value)
    {
        return (function ($getter) use ($value) {
            return $this->$getter($value);
        })->call($context, $getter);
    }

    /**
     * @return string
     */
    private function getBooleanGetter(): string
    {
        return 'is' . $this->camel;
    }

    /**
     * @param mixed|object $context
     * @param mixed $value
     * @return void
     */
    public function setValueTo($context, $value): void
    {
        if (\method_exists($context, $setter = $this->getPropertySetter())) {
            $this->setUsingSetter($context, $setter, $value);

            return;
        }

        $this->setToAttribute($context, $value);
    }

    /**
     * @return string
     */
    private function getPropertySetter(): string
    {
        return 'set' . $this->camel;
    }

    /**
     * @param object $context
     * @param string $setter
     * @param mixed $value
     * @return void
     */
    private function setUsingSetter($context, string $setter, $value): void
    {
        (function () use ($setter, $value) {
            $this->$setter($value);
        })->call($context);
    }

    /**
     * @param object $context
     * @param mixed $value
     */
    private function setToAttribute($context, $value): void
    {
        (function (string $name) use ($value) {
            return $this->$name = $value;
        })->call($context, $this->name);
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
        if ($this->matcher === null) {
            return true;
        }

        return $this->matcher->match($value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
