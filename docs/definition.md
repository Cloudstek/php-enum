# PHP Enum

## Definition

The `Cloudstek\Enum\Enum` base class takes care of all the work behind the scenes so all you have to do is extend your enum class from that and define your members using either properties, constants, methods or a mix of those.

All properties, constants and methods will be made into enumeration members unless you prefix them with `_`.

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
  
    private $inProgress = 'in_progress';
  
  	private function done()
    {
        return 'done';
    }
  
    private const _I_AM_IGNORED = 'hello';
}
```

To allow for autocompletion in IDEs, add the `@method` 

### Naming members

Every time you access a member (either directly, using `get()` or `has()`), the name will be normalised to match how you name your constants, properties or methods. This allows for case-insensitive access to the enum members.

```php
TaskStatus::has('toDo');        // true
TaskStatus::has('IN_PROGRESS'); // true
TaskStatus::has('DONE');        // true

TaskStatus::get('ToDo') === TaskStatus::TODO(); // true 
TaskStatus::toDo() === TaskStatus::TODO();      // true
```

Notice how `$inProgress` is defined yet you can access it using `IN_PROGRESS`.

Normalisation of names is controlled by several functions in the enum base class which you can override to suit your needs:

**normalizeConstantName(string $name): string**
For normalising to constant names. This defaults to upper snake case (e.g. FOO_BAR).

**normalizePropertyName(string $name): string**
For normalising to property names. This defaults to camel case (e.g. fooBar).

**normalizeMethodName(string $name): string**
For normalising to method names. This defaults to camel case (e.g. fooBar).

For example to use camelCase for constants:

```php
use Cloudstek\Enum\Enum;

/**
 * @method static self TODO()
 * @method static self IN_PROGRESS()
 * @method static self DONE()
 */
class TaskStatus extends Enum
{
    private const todo = 'todo';
    private const inProgress = 'in_progress';
    private const done = 'done';
  
    /**
     * Convert the member name to camelCase constant name.
     */
    protected static function normalizeConstantName(string $name): string
    {
        $parts = explode('_', strtolower($name), 2);

        if (count($parts) === 1) {
            return $parts[0];
        }

        return $parts[0].str_replace(' ', '', ucwords(str_replace('_', ' ', $parts[1])));
    }
}
```

