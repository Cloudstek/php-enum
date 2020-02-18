<?php

declare(strict_types=1);

namespace Cloudstek\Enum\Tests\Fixtures;

use Cloudstek\Enum\Enum;

abstract class AbstractTestEnum extends Enum
{
    public function __construct(string $name = '', $value = null)
    {
        parent::__construct($name, $value);
    }
}
