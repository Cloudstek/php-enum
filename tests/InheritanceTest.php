<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests;

use PHPUnit\Framework\TestCase;

class InheritanceTest extends TestCase
{
    /**
     * Test if enum has a member by instance
     */
    public function testHasByInstance()
    {
        $enum = new class() extends Fixtures\FooEnum {
            private $bar = 'bar';
        };

        // Inherited member
        $this->assertTrue($enum::has('FOO'));

        // Inherited member by own instance
        $this->assertTrue($enum::has($enum::FOO()));

        // Inherited member by parent instance
        $this->assertFalse($enum::has(Fixtures\FooEnum::FOO()));
    }

    /**
     * Test inherited member from different instance is not the same.
     */
    public function testInheritedMemberDifferentInstanceNotSame()
    {
        $enum = new class() extends Fixtures\FooEnum {
            private $bar = 'bar';
        };

        $this->assertInstanceOf(get_class($enum), $enum::FOO());

        // Inherited member, different instances
        $this->assertNotSame($enum::FOO(), Fixtures\FooEnum::FOO());
        $this->assertNotSame($enum::get('FOO'), Fixtures\FooEnum::FOO());
    }
}
