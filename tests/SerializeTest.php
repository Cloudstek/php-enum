<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests;

use PHPUnit\Framework\TestCase;

class SerializeTest extends TestCase
{
    /**
     * Test JSON serialize.
     */
    public function testJsonSerialize()
    {
        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
            private $otherFoo = 'other foo';

            private const BAR = 'bar';
            private const OTHER_BAR = 'other bar';

            private const LORUM = 'lorum const';
            private $lorum = 'lorum prop';

            private const VALUE_BOOL = false;
            private const VALUE_INT = 3;
            private const VALUE_ARRAY = ['foo'];
        };

        $this->assertEquals('"foo"', json_encode($enum::FOO()));
        $this->assertEquals('"other foo"', json_encode($enum::OTHER_FOO()));

        $this->assertEquals('"bar"', json_encode($enum::BAR()));
        $this->assertEquals('"other bar"', json_encode($enum::OTHER_BAR()));

        $this->assertEquals('"lorum const"', json_encode($enum::LORUM()));

        $this->assertEquals('false', json_encode($enum::VALUE_BOOL()));
        $this->assertEquals('3', json_encode($enum::VALUE_INT()));
        $this->assertEquals('["foo"]', json_encode($enum::VALUE_ARRAY()));
    }

    /**
     * Make sure that serialization is not allowed.
     */
    public function testSerialize()
    {
        $this->expectException(\Exception::class);

        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
        };

        serialize($enum);
    }

    /**
     * Make sure that serialization is not allowed.
     */
    public function testSleep()
    {
        $this->expectException(\LogicException::class);

        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
        };

        $enum->__sleep();
    }

    /**
     * Make sure that serialization is not allowed.
     */
    public function testWakeup()
    {
        $this->expectException(\LogicException::class);

        $enum = new class() extends Fixtures\AbstractTestEnum {
            private $foo = 'foo';
        };

        $enum->__wakeup();
    }
}
