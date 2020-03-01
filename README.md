# PHP Enum

> Enumeration support for PHP.

[![Build Status](https://img.shields.io/github/workflow/status/Cloudstek/php-enum/php)](https://github.com/Cloudstek/php-enum/actions) [![Coverage Status](https://coveralls.io/repos/github/Cloudstek/php-enum/badge.svg)](https://coveralls.io/github/Cloudstek/php-enum) ![GitHub tag (latest SemVer)](https://img.shields.io/github/v/tag/Cloudstek/php-enum?label=latest) ![Downloads](https://img.shields.io/packagist/dt/cloudstek/php-enum) ![GitHub](https://img.shields.io/github/license/Cloudstek/php-enum) ![GitHub stars](https://img.shields.io/github/stars/Cloudstek/php-enum)

This package adds support for [enumerations](https://en.wikipedia.org/wiki/Enumerated_type) to PHP, which are unfortunately not supported natively.

Using a simple class with constants alone doesn't allow you to use type hints meaning you still have to do extensive checks whether the value is expected. This package allows you to define enumerations the same way but allows for type hinting for example your method parameter. This way you can always be sure it holds a concrete set of members and values.

## Requirements

- PHP 7.1+
- Composer*

*\* Installation without composer is possible as the package consists of a single class, but is obviously not recommended.*

## Installation

Install the package through composer:

```bash
composer require cloudstek/php-enum
```

## Usage

### Definition

The `Cloudstek\Enum\Enum` base class takes care of all the work behind the scenes so all you have to do is extend your enum class from that and define your members using either properties, constants, methods or a mix of those.

Take for example this `TaskStatus` enum with three members: `TODO`, `IN_PROGRESS` and `DONE`. Each has a string value in this example but you're free to assign any kind of value you like.

```php
use Cloudstek\Enum\Enum;

/**
 * @method static self TODO()
 * @method static self IN_PROGRESS()
 * @method static self DONE()
 */
class TaskStatus extends Enum
{
    private const TODO = 'todo';
    private const IN_PROGRESS = 'in_progress';
    private const DONE = 'done';
}
```

*The doctype is only required for autocompletion in IDEs, not for the enum to function.*

Make sure you define your members as either `private` or `protected` to avoid confusion leading to direct access to a member's value instead of an instance, causing exceptions when your code expects an instance and not the value (such as the example below).

```php
TaskStatus::TODO !== TaskStatus::TODO()
```

```php
class Task
{
    /** @var TaskStatus */
    private $status;

    /**
     * Set status
     *
     * @param TaskStatus $status
     */
    public function setStatus(TaskStatus $status)
    {
        $this->status = $status;
    }

    // ..
}
```

Or if you need to be more flexible, the `get` method will intelligently return the member by name or if an object is given, check that it's the correct type.

```php
class Task
{
    /** @var TaskStatus */
    private $status;

    /**
     * Set status
     *
     * @param TaskStatus|string $status
     * 
     * @throws \UnexpectedValueException On unknown status.
     */
    public function setStatus($status)
    {
        $this->status = TaskStatus::get($status);
    }

    // ..
}
```

To read more about ways to define your members and how to name them, please see [docs/definition.md](docs/definition.md).

### Comparison

With enums you're always dealing with a single instance per member therefore you can compare them directly.

```php
// Compare by instance
TaskStatus::TODO() === TaskStatus::TODO();                 // true
TaskStatus::TODO() === TaskStatus::get('todo');            // true
TaskStatus::get('TODO') === TaskStatus::get('todo');       // true
TaskStatus::TODO() === TaskStatus::get(TaskStatus::TODO()) // true

TaskStatus::TODO() === TaskStatus::DONE();                 // false
TaskStatus::TODO() === TaskStatus::get('done');            // false

// Compare by value
(string) TaskStatus::TODO() === 'todo';                    // true
TaskStatus::TODO()->getValue() === 'todo';                 // true
```

### Inheritance

You should always define your enums as `final` classes to prevent other classes from inheriting from it. If you want other classes inheriting it, consider making it `abstract` and write `final` concrete classes that inherit from it.

Without making it final, your code could accept inherited enums when all you expected was the base class. This could lead to nasty bugs.

For example consider these enums:

```php
use Cloudstek\Enum\Enum;

class FooEnum extends Enum
{
    private const FOO = 'foo';
}

class BarEnum extends FooEnum
{
    private const BAR = 'bar';
}
```

Without making `FooEnum` final, your code could unintentionally accept `BarEnum` as well even though it is expecting `FooEnum`.

```php
class Foo
{
    public function doSomething(FooEnum $foo)
    {
        // Do something...
    }
}

$foo = new Foo();
$foo->doSomething(FooEnum::FOO()); // Allowed and OK, we were expecting FooEnum
$foo->doSomething(BarEnum::BAR()); // Allowed but not OK, we got BarEnum!
```

To prevent this and to make sure we always get `FooEnum` we should mark it final. Which doesn't mean it can't inherit anything else.

```php
use Cloudstek\Enum\Enum;

abstract class BaseEnum extends Enum
{
    private const HELLO = 'world';
}

final class FooEnum extends BaseEnum
{
    private const FOO = 'foo';
}

final class BarEnum extends BaseEnum
{
    private const BAR = 'bar';
}
```

Now we're sure we only get instances of `FooEnum`.

```php
class Foo
{
    public function doSomething(FooEnum $foo)
    {
      // Do something...
    }
}

$foo = new Foo();
$foo->doSomething(FooEnum::FOO()); // Allowed and OK, we were expecting FooEnum
$foo->doSomething(BarEnum::BAR()); // Fatal error
```

But in case we really don't care, as long as its base type is `BaseEnum`, we have to change the parameter type to `BaseEnum` explicitly like so:

```php
class Foo
{
    public function doSomething(BaseEnum $foo)
    {
      // Do something...
    }
}

$foo = new Foo();
$foo->doSomething(FooEnum::FOO()); // OK
$foo->doSomething(BarEnum::BAR()); // OK
```

### Storing data

If you store data containing an enum and you want to convert it back into an enum later, make sure to store the member name using `getName()` instead of storing its value. If you only care about the value, just store the value using `getValue()` or by casting it to a string (if possible).

```php
// Update task
$status = TaskStatus::TODO();

$db->update($task, [
    'status' => $status->getName() // 'status' => 'todo'
]);
```

```php
// Fetch task
$taskRow = $db->tasks->fetchOne(13); // [..., 'status' => 'todo', ...]

$task = new Task();
// ..
$task->setStatus(TaskStatus::get($taskRow['status']));

// or if you call TaskStatus::get() in Task::setStatus()
$task->setStatus($taskRow['status']);
```

## Support

You can support this project by contributing to open issues, submitting pull requests, giving this project a :star: or telling your friends about it.

If you have any ideas or issues, please open up an issue!

## Related projects

* [spatie/enum](https://github.com/spatie/enum)
* [myclabs/php-enum](https://github.com/myclabs/php-enum)
* [eloquent/enumeration](https://github.com/eloquent/enumeration)

