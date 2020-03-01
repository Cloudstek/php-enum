<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests;

use PHPUnit\Framework\TestCase;

class EqualityTest extends TestCase
{
    /**
     * Test same instances.
     */
    public function testSameInstance()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
            private const BAR = 'bar';

            private function lorum()
            {
                return 'lorum';
            }
        };

        $this->assertInstanceOf(get_class($enum), $enum::FOO());
        $this->assertSame($enum::FOO(), $enum::FOO());
        $this->assertSame($enum::get('FOO'), $enum::FOO());

        $this->assertInstanceOf(get_class($enum), $enum::BAR());
        $this->assertSame($enum::BAR(), $enum::BAR());
        $this->assertSame($enum::get('BAR'), $enum::BAR());

        $this->assertInstanceOf(get_class($enum), $enum::LORUM());
        $this->assertSame($enum::LORUM(), $enum::LORUM());
        $this->assertSame($enum::get('LORUM'), $enum::LORUM());
    }

    /**
     * Test same instances case-insensitive when calling static.
     */
    public function testSameInstanceStaticCaseInsensitive()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
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

        $this->assertSame($enum::FOO(), $enum::foo());
        $this->assertSame($enum::OTHER_FOO(), $enum::other_foo());
        $this->assertSame($enum::OTHER_FOO(), $enum::Other_FoO());
        $this->assertSame($enum::OTHER_FOO(), $enum::otherFoo());
        $this->assertSame($enum::OTHER_FOO(), $enum::OtherFoo());

        $this->assertSame($enum::BAR(), $enum::bar());
        $this->assertSame($enum::OTHER_BAR(), $enum::other_bar());
        $this->assertSame($enum::OTHER_BAR(), $enum::Other_BaR());
        $this->assertSame($enum::OTHER_BAR(), $enum::otherBar());
        $this->assertSame($enum::OTHER_BAR(), $enum::OtherBar());

        $this->assertSame($enum::LORUM(), $enum::lorum());
        $this->assertSame($enum::OTHER_LORUM(), $enum::other_lorum());
        $this->assertSame($enum::OTHER_LORUM(), $enum::Other_LoRum());
        $this->assertSame($enum::OTHER_LORUM(), $enum::otherLorum());
        $this->assertSame($enum::OTHER_LORUM(), $enum::OtherLorum());
    }

    /**
     * Test same instances case-insensitive when calling with get().
     */
    public function testSameInstanceGetterCaseInsensitive()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
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

        $this->assertSame($enum::FOO(), $enum::get('foo'));
        $this->assertSame($enum::OTHER_FOO(), $enum::get('other_foo'));
        $this->assertSame($enum::OTHER_FOO(), $enum::get('Other_FoO'));
        $this->assertSame($enum::OTHER_FOO(), $enum::get('otherFoo'));
        $this->assertSame($enum::OTHER_FOO(), $enum::get('OtherFoo'));

        $this->assertSame($enum::BAR(), $enum::get('bar'));
        $this->assertSame($enum::OTHER_BAR(), $enum::get('other_bar'));
        $this->assertSame($enum::OTHER_BAR(), $enum::get('Other_BaR'));
        $this->assertSame($enum::OTHER_BAR(), $enum::get('otherBar'));
        $this->assertSame($enum::OTHER_BAR(), $enum::get('OtherBar'));

        $this->assertSame($enum::LORUM(), $enum::get('lorum'));
        $this->assertSame($enum::OTHER_LORUM(), $enum::get('other_lorum'));
        $this->assertSame($enum::OTHER_LORUM(), $enum::get('Other_LoRum'));
        $this->assertSame($enum::OTHER_LORUM(), $enum::get('otherLorum'));
        $this->assertSame($enum::OTHER_LORUM(), $enum::get('OtherLorum'));
    }

    /**
     * Test same instances when calling with get() by instance.
     */
    public function testSameInstanceGetterByInstance()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
            private const BAR = 'bar';

            private function lorum()
            {
                return 'lorum';
            }
        };

        $this->assertSame($enum::get($enum::FOO()), $enum::FOO());
        $this->assertSame($enum::get($enum::BAR()), $enum::BAR());
        $this->assertSame($enum::get($enum::LORUM()), $enum::LORUM());
    }

    /**
     * Test get() will throw an exception when calling it with a different instance.
     */
    public function testSameInstanceGetterByDifferentInstanceThrowing()
    {
        $this->expectException(\UnexpectedValueException::class);

        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
            private const BAR = 'bar';

            private function lorum()
            {
                return 'lorum';
            }
        };

        $otherEnum = new class() extends Fixtures\AbstractTestEnum {
            private $hello = 'world';
        };

        $this->assertNotSame($enum::get($otherEnum::HELLO()), $otherEnum::HELLO());
    }

    /**
     * Test different instances with same value to not equal.
     */
    public function testSameValueDifferentInstance()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
            private $otherFoo = 'foo';

            private const BAR = 'bar';
            private const OTHER_BAR = 'bar';

            private function lorum()
            {
                return 'lorum';
            }

            private function otherLorum()
            {
                return 'lorum';
            }
        };

        $this->assertEquals('foo', $enum::FOO()->getValue());
        $this->assertEquals('foo', $enum::OTHER_FOO()->getValue());
        $this->assertNotSame($enum::FOO(), $enum::OTHER_FOO());

        $this->assertEquals('bar', $enum::BAR()->getValue());
        $this->assertEquals('bar', $enum::OTHER_BAR()->getValue());
        $this->assertNotSame($enum::BAR(), $enum::OTHER_BAR());

        $this->assertEquals('lorum', $enum::LORUM()->getValue());
        $this->assertEquals('lorum', $enum::OTHER_LORUM()->getValue());
        $this->assertNotSame($enum::LORUM(), $enum::OTHER_LORUM());
    }
}
