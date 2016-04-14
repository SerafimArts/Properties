<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 17:20
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Unit\Stub;

use Serafim\Properties\Properties;

/**
 * Class ReadWrite
 * @package Serafim\Properties\Unit\Stub
 * @property-read string $some
 * @property-read string $getter
 * @property-write string $any
 * @property-write string $setter
 */
class ReadWrite
{
    use Properties;

    protected $some = 'some';

    protected $any = 'any';

    public function getGetter()
    {
        return 'getter';
    }

    public function setSetter($newValue)
    {
        $this->any = $newValue;
    }

    public function getWritablePropertyValue()
    {
        return $this->any;
    }
}
