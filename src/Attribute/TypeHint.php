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
abstract class TypeHint implements HintInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Matchable[]
     */
    protected $matchers = [];

    /**
     * TypeHint constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Matchable $matcher
     * @return Disjunction
     */
    public function addMatcher(Matchable $matcher): Matchable
    {
        $this->matchers[] = $matcher;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    protected function matchScalar(string $name, $value): bool
    {
        switch (true) {
            case $this->isBuiltin($name):
                return $this->matchBuiltin($name, $value);

            case $name === 'mixed':
                return true;

            case $name === 'null':
            case $name === 'void':
                return $value === null;

            case \class_exists($name):
                return $value instanceof $name;

            default:
                @\trigger_error('Unrecognized type-hint "' . $name . '"');
                return true;
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $this->matchScalar($this->name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isBuiltin(string $name): bool
    {
        return \function_exists('\\is_' . $name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    private function matchBuiltin(string $name, $value): bool
    {
        $function = '\\is_' . $name;

        return $function($value);
    }
}
