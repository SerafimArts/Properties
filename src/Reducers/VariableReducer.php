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
 * Class VariableReducer
 */
class VariableReducer implements ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'DocBlockVariable';
    }

    /**
     * @param RuleInterface $rule
     * @return string
     */
    public function reduce(RuleInterface $rule): string
    {
        return $rule->getChild(0)->getValue(1);
    }
}
