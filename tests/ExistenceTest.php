<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests;

use PHPUnit\Framework\TestCase;

class ExistenceTest extends TestCase
{
    /**
     * Test if enum has a member by name.
     */
    public function testHasByName()
    {
        $enum = new class() extends AbstractTestEnum {
            private $foo = 'foo';
            private $otherFoo = 'other foo';

            private const BAR = 'bar';
            private const OTHER_BAR = 'other bar';

            private function lorum()
            {
                return 'lorum';
            }

            private function otherLorum()
            {
                return 'other lorum';
            }
        };

        // Property
        $this->assertTrue($enum::has('FOO'));
        $this->assertTrue($enum::has('foo'));

        // snake_case property
        $this->assertTrue($enum::has('OTHER_FOO'));
        $this->assertTrue($enum::has('other_foo'));
        $this->assertTrue($enum::has('Other_FoO'));

        // camelCase property
        $this->assertTrue($enum::has('otherFoo'));
        $this->assertTrue($enum::has('OtherFoo'));

        // Constant
        $this->assertTrue($enum::has('BAR'));
        $this->assertTrue($enum::has('bar'));

        // snake_case constant
        $this->assertTrue($enum::has('OTHER_BAR'));
        $this->assertTrue($enum::has('other_bar'));
        $this->assertTrue($enum::has('Other_BaR'));

        // camelCase constant
        $this->assertTrue($enum::has('otherBar'));
        $this->assertTrue($enum::has('OtherBar'));

        // Method
        $this->assertTrue($enum::has('LORUM'));
        $this->assertTrue($enum::has('lorum'));

        // snake_case method
        $this->assertTrue($enum::has('OTHER_LORUM'));
        $this->assertTrue($enum::has('other_lorum'));
        $this->assertTrue($enum::has('Other_LoRum'));

        // camelCase method
        $this->assertTrue($enum::has('otherLorum'));
        $this->assertTrue($enum::has('OtherLorum'));

        // Method names are case insensitive
        $this->assertTrue($enum::has('LorUM'));
        $this->assertTrue($enum::has('OtHErLOrum'));
    }

    /**
     * Test if enum does not have a member by name.
     */
    public function testHasByInvalidName()
    {
        $enum = new class() extends AbstractTestEnum {
            private $foo = 'foo';
            private $otherFoo = 'other foo';

            private const BAR = 'bar';
            private const OTHER_BAR = 'other bar';

            private function lorum()
            {
                return 'lorum';
            }

            private function otherLorum()
            {
                return 'other lorum';
            }
        };

        // Completely missing.
        $this->assertFalse($enum::has('bleep'));

        // Internal properties, not allowed
        $this->assertFalse($enum::has('_name'));
        $this->assertFalse($enum::has('_NAME'));
        $this->assertFalse($enum::has('_value'));
        $this->assertFalse($enum::has('_VALUE'));
        $this->assertFalse($enum::has('_instances'));
        $this->assertFalse($enum::has('_INSTANCES'));

        // Would be interpreted as camelCase and normalize to e.g. FO_O, which is missing.
        $this->assertFalse($enum::has('FoO'));
        $this->assertFalse($enum::has('OtHerFoO'));
        $this->assertFalse($enum::has('BaR'));
        $this->assertFalse($enum::has('OtHErBar'));
    }

    /**
     * Test getting an invalid member when calling static.
     */
    public function testNonExistingMemberStatic()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('BAR is not an enum member of');

        $enum = new class() extends AbstractTestEnum {
            private $foo = 'foo';
        };

        $enum::BAR();
    }

    /**
     * Test getting an invalid member when calling static.
     */
    public function testInvalidMemberStatic()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('_NAME is not an enum member of');

        $enum = new class() extends AbstractTestEnum {
            private $foo = 'foo';
        };

        $enum::_NAME();
    }

    /**
     * Test getting an invalid member when calling with get().
     */
    public function testNonExistingMemberByGetter()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('BAR is not an enum member of');

        $enum = new class() extends AbstractTestEnum {
            private $foo = 'foo';
        };

        $enum::get('BAR');
    }

    /**
     * Test getting an invalid member when calling with get().
     */
    public function testInvalidMemberByGetter()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('_NAME is not an enum member of');

        $enum = new class() extends AbstractTestEnum {
            private $foo = 'foo';
        };

        $enum::get('_NAME');
    }
}
