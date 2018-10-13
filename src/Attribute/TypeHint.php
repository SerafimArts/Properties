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
 * Class TypeHint
 */
class TypeHint implements HintInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $iterable;

    /**
     * @var Matchable[]
     */
    private $matchers = [];

    /**
     * TypeHint constructor.
     * @param string $name
     * @param bool $iterable
     */
    public function __construct(string $name, bool $iterable = false)
    {
        $this->name = $name;
        $this->iterable = $iterable;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isIterable(): bool
    {
        return $this->iterable;
    }

    /**
     * @param Matchable $matcher
     * @return OrTypeHint
     */
    public function addMatcher(Matchable $matcher): Matchable
    {
        $this->matchers[] = $matcher;

        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $this->isIterable() ? $this->matchIterable($value) : $this->matchScalar($value);
    }

    /**
     * @param iterable|mixed $values
     * @return bool
     */
    private function matchIterable($values): bool
    {
        if (! \is_iterable($values)) {
            return false;
        }

        foreach ($values as $value) {
            if (! $this->matchScalar($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function matchScalar($value): bool
    {
        switch (true) {
            case $this->isBuiltin():
                return $this->matchBuiltin($value);

            case $this->name === 'mixed':
                return true;

            case $this->name === 'null':
            case $this->name === 'void':
                return $value === null;

            case \class_exists($this->name):
                return $value instanceof $this->name;

            default:
                @\trigger_error('Unrecognized type-hint "' . $this->name . '"');
                return true;
        }
    }

    /**
     * @return bool
     */
    private function isBuiltin(): bool
    {
        return \function_exists('\\is_' . $this->name);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function matchBuiltin($value): bool
    {
        $function = '\\is_' . $this->name;

        return $function($value);
    }
}
