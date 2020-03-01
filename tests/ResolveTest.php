<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests;

use PHPUnit\Framework\TestCase;

class ResolveTest extends TestCase
{
    /**
     * Test resolve constant before property.
     *
     * Make sure that constants are resolved before properties.
     */
    public function testResolveConstantBeforeProperty()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private const FOO = 'foo const';
            private $foo = 'foo prop';

            private function foo()
            {
                return 'foo method';
            }
        };

        $this->assertEquals('foo const', $enum::FOO()->getValue());
        $this->assertEquals('foo const', (string) $enum::FOO());
        $this->assertSame($enum::FOO(), $enum::FOO());
        $this->assertSame($enum::FOO(), $enum::get('FOO'));
    }

    /**
     * Test resolve property before method.
     *
     * Make sure that properties are resolved before methods.
     */
    public function testResolvePropertyBeforeMethod()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo prop';

            private function foo()
            {
                return 'foo method';
            }
        };

        $this->assertEquals('foo prop', $enum::FOO()->getValue());
        $this->assertEquals('foo prop', (string) $enum::FOO());
        $this->assertSame($enum::FOO(), $enum::FOO());
        $this->assertSame($enum::FOO(), $enum::get('FOO'));
    }

    /**
     * Test resolve methods last.
     *
     * Make sure that methods are resolved last.
     */
    public function testResolveMethodsLast()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private function foo()
            {
                return 'foo method';
            }
        };

        $this->assertEquals('foo method', $enum::FOO()->getValue());
        $this->assertEquals('foo method', (string) $enum::FOO());
        $this->assertSame($enum::FOO(), $enum::FOO());
        $this->assertSame($enum::FOO(), $enum::get('FOO'));
    }
}
