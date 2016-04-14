<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 14:49
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Unit;

/**
 * Class AbstractTestCase
 * @package Serafim\Properties\Unit
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return bool
     */
    public function canCatchPhpErrors()
    {
        return version_compare('7.0', phpversion()) < 0;
    }
}
