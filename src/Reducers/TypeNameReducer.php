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

/**
 * Class TypeNameReducer
 */
class TypeNameReducer implements ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'Type';
    }

    /**
     * @param RuleInterface $rule
     * @return string
     */
    public function reduce(RuleInterface $rule): string
    {
        $chunks = \iterator_to_array($this->getChunks($rule));

        return \implode('\\', $chunks);
    }

    /**
     * @param RuleInterface $rule
     * @return \Traversable|string[]
     */
    private function getChunks(RuleInterface $rule): \Traversable
    {
        foreach ($rule->getChildren() as $child) {
            yield $child->getValue();
        }
    }
}
