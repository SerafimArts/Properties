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
use Serafim\Properties\Attribute\GenericTypeHint;
use Serafim\Properties\Attribute\Matchable;
use Serafim\Properties\Attribute\TypeHint;
use Serafim\Properties\Reducers\ReducerInterface;

/**
 * Class GenericReducer
 */
class GenericReducer implements ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'Generic';
    }

    /**
     * @param RuleInterface $rule
     * @return \Generator|Matchable
     */
    public function reduce(RuleInterface $rule): \Generator
    {
        /** @var string $name */
        $name = yield $rule->first('Type');

        $keyValue = [];

        foreach ($rule->first('GenericArguments') as $argument) {
            $keyValue[] = yield $argument;
        }

        return new GenericTypeHint($name, ...$keyValue);
    }
}
