<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests;

use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    /**
     * Test name when calling static.
     */
    public function testNameStatic()
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

        $this->assertEquals('FOO', $enum::FOO()->getName());
        $this->assertEquals('FOO', $enum::foo()->getName());
        $this->assertEquals('OTHER_FOO', $enum::OTHER_FOO()->getName());
        $this->assertEquals('OTHER_FOO', $enum::other_foo()->getName());
        $this->assertEquals('OTHER_FOO', $enum::Other_FoO()->getName());
        $this->assertEquals('OTHER_FOO', $enum::otherFoo()->getName());
        $this->assertEquals('OTHER_FOO', $enum::OtherFoo()->getName());

        $this->assertEquals('BAR', $enum::BAR()->getName());
        $this->assertEquals('BAR', $enum::bar()->getName());
        $this->assertEquals('OTHER_BAR', $enum::OTHER_BAR()->getName());
        $this->assertEquals('OTHER_BAR', $enum::other_bar()->getName());
        $this->assertEquals('OTHER_BAR', $enum::Other_BaR()->getName());
        $this->assertEquals('OTHER_BAR', $enum::otherBar()->getName());
        $this->assertEquals('OTHER_BAR', $enum::OtherBar()->getName());

        $this->assertEquals('LORUM', $enum::LORUM()->getName());
        $this->assertEquals('LORUM', $enum::lorum()->getName());
        $this->assertEquals('OTHER_LORUM', $enum::OTHER_LORUM()->getName());
        $this->assertEquals('OTHER_LORUM', $enum::OthER_LoRUm()->getName());
        $this->assertEquals('OTHER_LORUM', $enum::other_lorum()->getName());
        $this->assertEquals('OTHER_LORUM', $enum::otherLorum()->getName());
        $this->assertEquals('OTHER_LORUM', $enum::OtherLorum()->getName());
    }

    /**
     * Test name when calling with get().
     */
    public function testNameWithGetter()
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

        $this->assertEquals('FOO', $enum::get('FOO')->getName());
        $this->assertEquals('FOO', $enum::get('foo')->getName());
        $this->assertEquals('OTHER_FOO', $enum::get('OTHER_FOO')->getName());
        $this->assertEquals('OTHER_FOO', $enum::get('other_foo')->getName());
        $this->assertEquals('OTHER_FOO', $enum::get('Other_FoO')->getName());
        $this->assertEquals('OTHER_FOO', $enum::get('otherFoo')->getName());
        $this->assertEquals('OTHER_FOO', $enum::get('OtherFoo')->getName());

        $this->assertEquals('BAR', $enum::get('BAR')->getName());
        $this->assertEquals('BAR', $enum::get('bar')->getName());
        $this->assertEquals('OTHER_BAR', $enum::get('OTHER_BAR')->getName());
        $this->assertEquals('OTHER_BAR', $enum::get('other_bar')->getName());
        $this->assertEquals('OTHER_BAR', $enum::get('Other_BaR')->getName());
        $this->assertEquals('OTHER_BAR', $enum::get('otherBar')->getName());
        $this->assertEquals('OTHER_BAR', $enum::get('OtherBar')->getName());

        $this->assertEquals('LORUM', $enum::get('LORUM')->getName());
        $this->assertEquals('LORUM', $enum::get('lorum')->getName());
        $this->assertEquals('OTHER_LORUM', $enum::get('OTHER_LORUM')->getName());
        $this->assertEquals('OTHER_LORUM', $enum::get('OthER_LoRUm')->getName());
        $this->assertEquals('OTHER_LORUM', $enum::get('other_lorum')->getName());
        $this->assertEquals('OTHER_LORUM', $enum::get('otherLorum')->getName());
        $this->assertEquals('OTHER_LORUM', $enum::get('OtherLorum')->getName());
    }
}
