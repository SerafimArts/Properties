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
 * Interface Matchable
 */
interface Matchable
{
    /**
     * @param Matchable $matchable
     * @return Matchable
     */
    public function addMatcher(Matchable $matchable): Matchable;

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool;
}
