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
 * Class ArrayTypeHint
 */
class ArrayTypeHint extends TypeHint
{
    /**
     * @param iterable|mixed $values
     * @return bool
     */
    public function match($values): bool
    {
        if (! \is_iterable($values)) {
            return false;
        }

        foreach ($values as $value) {
            if (! parent::match($value)) {
                return false;
            }
        }

        return true;
    }
}
