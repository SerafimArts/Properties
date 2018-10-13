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
namespace Serafim\Properties\Tests\Stub;

use Serafim\Properties\Properties;

/**
 * Class Readable
 * @property-read string $some
 * @property-read int $any
 * @property-read bool $boolMethod
 * @property-read bool $bool_method
 */
class Readable
{
    use Properties;

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
