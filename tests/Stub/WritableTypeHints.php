<?php
/**
 * This file is part of Properties package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 14:53
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Properties\Tests\Stub;

use Serafim\Properties\Properties;

/**
 * Class WritableChild
 * @package Serafim\Properties\Tests\Stub
 *
 * @property int $intValue
 * @property null $nullValue
 * @property bool $boolValue
 * @property array $arrayValue
 * @property string $stringValue
 * @property object $objectValue
 * @property resource $resourceValue
 *
 * @property int|bool $intOrBoolValue
 * @property string|bool|array $stringOrBoolOrArrayValue
 * @property WritableTypeHints[] $arrayInstanceOfSelfValue
 */
class WritableTypeHints
{
    use Properties;

    protected $intValue;
    protected $nullValue;
    protected $boolValue;
    protected $arrayValue;
    protected $stringValue;
    protected $objectValue;
    protected $resourceValue;
    protected $intOrBoolValue;
    protected $stringOrBoolOrArrayValue;
    protected $arrayInstanceOfSelfValue;
}
