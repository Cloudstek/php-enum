<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests;

use PHPUnit\Framework\TestCase;

class MiscTest extends TestCase
{
    /**
     * Test different value types.
     */
    public function testValueTypes()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $stringValue = 'foo';
            private $boolValue = true;
            private $intValue = 3;
            private $arrayValue = ['apple', 'pie'];

            private const VALUE_STRING = 'bar';
            private const VALUE_BOOL = false;
            private const VALUE_INT = 10;
            private const VALUE_ARRAY = ['foo', 'bar'];
        };

        $this->assertEquals('foo', $enum::STRING_VALUE()->getValue());
        $this->assertEquals(true, $enum::BOOL_VALUE()->getValue());
        $this->assertEquals(3, $enum::INT_VALUE()->getValue());
        $this->assertEquals(['apple', 'pie'], $enum::ARRAY_VALUE()->getValue());

        $this->assertEquals('bar', $enum::VALUE_STRING()->getValue());
        $this->assertEquals(false, $enum::VALUE_BOOL()->getValue());
        $this->assertEquals(10, $enum::VALUE_INT()->getValue());
        $this->assertEquals(['foo', 'bar'], $enum::VALUE_ARRAY()->getValue());
    }

    /**
     * Test toString value.
     */
    public function testToStringValue()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
            private $otherFoo = 'other foo';

            private const BAR = 'bar';
            private const OTHER_BAR = 'other bar';

            private const LORUM = 'lorum const';
            private $lorum = 'lorum prop';
        };

        $this->assertEquals('foo', (string) $enum::FOO());
        $this->assertEquals('other foo', (string) $enum::OTHER_FOO());

        $this->assertEquals('bar', (string) $enum::BAR());
        $this->assertEquals('other bar', (string) $enum::OTHER_BAR());

        $this->assertEquals('lorum const', (string) $enum::LORUM());
    }

    /**
     * Make sure that cloning is not allowed.
     */
    public function testClone()
    {
        $this->expectException(\LogicException::class);

        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
        };

        $foo = clone $enum;
    }

    /**
     * Test member method called once.
     *
     * Make sure that member methods are only called once to retreive their value. Any subsequent call to get the value
     * should lead to the same result.
     */
    public function testMemberMethodCalledOnce()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private function foo()
            {
                return bin2hex(random_bytes(4));
            }
        };

        $value = $enum::FOO()->getValue();

        $this->assertEquals($enum::FOO()->getValue(), $value);
        $this->assertEquals($enum::FOO()->getValue(), $enum::FOO()->getValue());
        $this->assertEquals((string) $enum::FOO(), $enum::FOO()->getValue());
    }
}
