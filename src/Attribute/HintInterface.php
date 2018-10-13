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
 * Interface HintInterface
 */
interface HintInterface extends Matchable
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return bool
     */
    public function isIterable(): bool;
}
