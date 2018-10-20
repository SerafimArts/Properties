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
namespace Serafim\Properties\Tests;

use Serafim\Properties\Tests\Stub\WritableTypeHints;

/**
 * Class WriteTypeHintsTestCase
 */
class WriteTypeHintsTestCase extends AbstractTestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testWritePrimitives(): void
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

        $mock->resourceValue = \fopen(__FILE__, 'r');
        $this->assertTrue(\is_resource($mock->resourceValue));
        fclose($mock->resourceValue);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testConditionalTypes(): void
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

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNegativeWritePrimitives(): void
    {
        $obj = new \stdClass();
        $mock = new WritableTypeHints();

        try {
            $hasError = false;
            $mock->intValue = $obj;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->nullValue = $obj;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->boolValue = $obj;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->arrayValue = $obj;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->stringValue = $obj;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->objectValue = null;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->objectValue = [1, 2, 3];
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }

        try {
            $hasError = false;
            $mock->resourceValue = '23';
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError);
        }
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNegativeConditionalTypes(): void
    {
        $mock = new WritableTypeHints();

        try {
            $hasError = false;
            $mock->intOrBoolValue = null;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError, 'int|bool $prop = null');
        }

        try {
            $hasError = false;
            $mock->stringOrBoolOrArrayValue = 23;
        } catch (\Throwable $e) {
            $hasError = true;
        } finally {
            $this->assertTrue($hasError, 'string|bool|array $prop = 23');
        }
    }

}
