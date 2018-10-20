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
use Serafim\Properties\Attribute\Attribute;
use Serafim\Properties\Attribute\Matchable;

/**
 * Class DocBlockReducer
 */
class DocBlockReducer implements ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'DocBlock';
    }

    /**
     * @param RuleInterface $rule
     * @return mixed|string
     */
    public function reduce(RuleInterface $rule)
    {
        /** @var int $type */
        $type = yield $rule->first('DocBlockTitle');

        /** @var string $name */
        $name = yield $rule->first('DocBlockVariable');

        /** @var Matchable $hint */
        $hint = yield $rule->first('TypeHint');

        $attribute = new Attribute($name, $type);

        if ($hint === null) {
            return $attribute;
        }

        return $attribute->addMatcher($hint);
    }
}
