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
 * Interface AttributeInterface
 */
interface AttributeInterface extends Matchable
{
    public const TYPE_UNDEFINED = 0;
    public const TYPE_PROPERTY = 1;
    public const TYPE_READABLE = 2;
    public const TYPE_WRITABLE = 3;

    /**
     * @return bool
     */
    public function isReadable(): bool;

    /**
     * @return bool
     */
    public function isWritable(): bool;

    /**
     * @return string
     */
    public function getName(): string;
}
