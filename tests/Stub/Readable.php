<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 14:50
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Unit\Stub;

use Serafim\Properties\Getters;

/**
 * Class Readable
 * @package Serafim\Properties\Unit\Stub
 * @property-read string $some
 * @property-read int $any
 * @property-read bool $boolMethod
 * @property-read bool $bool_method
 */
class Readable
{
    use Getters;

    /**
     * @var string
     */
    protected $some = 'property';

    /**
     * @return int
     */
    public function getAny()
    {
        return 23;
    }

    /**
     * @return bool
     */
    public function isBoolMethod()
    {
        return true;
    }
}
