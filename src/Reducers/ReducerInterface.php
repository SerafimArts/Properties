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
 * Interface ReducerInterface
 */
interface ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool;

    /**
     * @param RuleInterface $rule
     * @return mixed
     */
    public function reduce(RuleInterface $rule);
}
