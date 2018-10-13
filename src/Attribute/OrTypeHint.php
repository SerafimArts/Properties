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
 * Class OrTypeHint
 */
class OrTypeHint implements Matchable
{
    /**
     * @var array|Matchable[]
     */
    private $matchable = [];

    /**
     * OrTypeHint constructor.
     * @param Matchable $matcher
     */
    public function __construct(Matchable $matcher)
    {
        $this->addMatcher($matcher);
    }

    /**
     * @param Matchable $matcher
     * @return OrTypeHint
     */
    public function addMatcher(Matchable $matcher): Matchable
    {
        if ($matcher instanceof self) {
            $this->matchable = \array_merge($this->matchable, $matcher->matchable);
        } else {
            $this->matchable[] = $matcher;
        }

        return $this;
    }


    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        foreach ($this->matchable as $matcher) {
            if ($matcher->match($value)) {
                return true;
            }
        }

        return false;
    }
}
