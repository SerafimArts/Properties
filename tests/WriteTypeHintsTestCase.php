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
namespace Serafim\Properties\Unit;

use Serafim\Properties\Unit\Stub\WritableTypeHints;

/**
 * Class WriteTypeHintsTestCase
 * @package Serafim\Properties\Unit
 */
class WriteTypeHintsTestCase extends AbstractTestCase
{
    public function testWritePrimitives()
    {
        $obj = new \stdClass();
        $mock = new WritableTypeHints();

        $mock->intValue = 23;
        $this->assertEquals($mock->intValue, 23);

        $mock->nullValue = null;
        $this->assertEquals($mock->nullValue, null);

        $mock->boolValue = true;
        $this->assertEquals($mock->boolValue, true);

        $mock->arrayValue = [1, 2, 3];
        $this->assertEquals($mock->arrayValue, [1, 2, 3]);

        $mock->stringValue = 'hello';
        $this->assertEquals($mock->stringValue, 'hello');

        $mock->objectValue = $obj;
        $this->assertEquals($mock->objectValue, $obj);

        $mock->objectValue = $mock;
        $this->assertEquals($mock->objectValue, $mock);

        $mock->resourceValue = fopen(__FILE__, 'r');
        $this->assertTrue(is_resource($mock->resourceValue));
        fclose($mock->resourceValue);
    }

    public function testConditionalTypes()
    {
        $testArray = [new WritableTypeHints(), new WritableTypeHints()];
        $mock = new WritableTypeHints();

        $mock->intOrBoolValue = 42;
        $this->assertEquals($mock->intOrBoolValue, 42);
        $mock->intOrBoolValue = false;
        $this->assertEquals($mock->intOrBoolValue, false);

        $mock->stringOrBoolOrArrayValue = 'asdasd';
        $this->assertEquals($mock->stringOrBoolOrArrayValue, 'asdasd');
        $mock->stringOrBoolOrArrayValue = true;
        $this->assertEquals($mock->stringOrBoolOrArrayValue, true);
        $mock->stringOrBoolOrArrayValue = [1, 2, 3];
        $this->assertEquals($mock->stringOrBoolOrArrayValue, [1, 2, 3]);

        $mock->arrayInstanceOfSelfValue = [1, 2, 3, 4];
        $this->assertEquals($mock->arrayInstanceOfSelfValue, [1, 2, 3, 4]);
        $mock->arrayInstanceOfSelfValue = $testArray;
        $this->assertEquals($mock->arrayInstanceOfSelfValue, $testArray);
    }

    public function testNegativeWritePrimitives()
    {
        $obj = new \stdClass();
        $mock = new WritableTypeHints();

        try {
            $hasError = false;
            $mock->intValue = $obj;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->nullValue = $obj;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->boolValue = $obj;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->arrayValue = $obj;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->stringValue = $obj;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->objectValue = null;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->objectValue = [1, 2, 3];
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->resourceValue = '23';
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }
    }

    public function testNegativeConditionalTypes()
    {
        $mock = new WritableTypeHints();

        try {
            $hasError = false;
            $mock->intOrBoolValue = null;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError, 'int|bool $prop = null');
        }

        try {
            $hasError = false;
            $mock->stringOrBoolOrArrayValue = 23;
        } catch (\TypeError $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError, 'string|bool|array $prop = 23');
        }
    }

}
