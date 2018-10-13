<?php
/**
 * This file is part of Properties package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Properties\Reducers\TypeHint;

use Railt\Parser\Ast\RuleInterface;
use Serafim\Properties\Attribute\AndTypeHint;
use Serafim\Properties\Attribute\Matchable;
use Serafim\Properties\Attribute\OrTypeHint;
use Serafim\Properties\Reducers\ReducerInterface;

/**
 * Class AndHintReducer
 */
class AndHintReducer implements ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'And';
    }

    /**
     * @param RuleInterface $rule
     * @return \Generator|Matchable
     */
    public function reduce(RuleInterface $rule): \Generator
    {
        return new AndTypeHint(yield $rule->getChild(0));
    }
}
