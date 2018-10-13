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
use Serafim\Properties\Attribute\AttributeInterface;

/**
 * Class DocTitleReducer
 */
class DocTitleReducer implements ReducerInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return $rule->getName() === 'DocBlockTitle';
    }

    /**
     * @param RuleInterface $rule
     * @return int
     */
    public function reduce(RuleInterface $rule): int
    {
        $title = $rule->getChild(0)->getValue(1);

        switch ($title) {
            case 'property':
                return AttributeInterface::TYPE_PROPERTY;

            case 'property-read':
                return AttributeInterface::TYPE_READABLE;

            case 'property-write':
                return AttributeInterface::TYPE_WRITABLE;

            default:
                return AttributeInterface::TYPE_UNDEFINED;
        }
    }
}
