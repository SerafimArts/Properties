<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties\Reducers;

use Railt\Parser\Ast\RuleInterface;
use Serafim\Properties\Attribute\Matchable;
use Serafim\Properties\Attribute\TypeHint;

/**
 * Class TypeHintReducer
 */
class TypeHintReducer implements ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'TypeHint';
    }

    /**
     * @param RuleInterface $rule
     * @return \Generator|TypeHint
     */
    public function reduce(RuleInterface $rule): \Generator
    {
        $hints = [];

        foreach ($rule->getChildren() as $child) {
            $hints[] = yield $child;
        }

        return $this->pack($hints);
    }

    /**
     * @param array|Matchable[] $hints
     * @return mixed
     */
    private function pack(array $hints)
    {
        if (\count($hints) > 1) {
            [$child, $parent] = $hints;

            $parent->addMatcher($child);

            return $parent;
        }

        return $hints[0];
    }
}
