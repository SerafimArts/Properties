<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 15:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Tests\Stub;

use Serafim\Properties\Properties;

/**
 * Class Writable
 * @package Serafim\Properties\Tests\Stub
 * @property-write int $some
 * @property-write $any
 */
class Writable
{
    use Properties;

    /**
     * @var int
     */
    protected $some = 23;

    /**
     * @var mixed
     */
    private $anySavedValue;

    /**
     * @param $value
     */
    public function setAny($value)
    {
        $this->anySavedValue = $value;
    }

    /**
     * @return mixed
     */
    public function getAny()
    {
        return $this->anySavedValue;
    }
}
