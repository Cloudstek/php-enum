<?php

declare(strict_types=1);

namespace Cloudstek\Enum;

abstract class Enum implements \JsonSerializable
{
    /**
     * Enum instances.
     *
     * @var array<string, array<string, Enum>>
     */
    private static $_instances = [];

    /**
     * Name.
     *
     * @var string
     */
    private $_name;

    /**
     * Value.
     *
     * @var mixed
     */
    private $_value;

    /**
     * Enum.
     *
     * @param string $name
     * @param mixed  $value
     */
    protected function __construct(string $name, $value = null)
    {
        $this->_name = $name;
        $this->_value = $value;
    }

    /**
     * Get name.
     *
     * @return string
     */
    final public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Get value.
     *
     * @return mixed
     */
    final public function getValue()
    {
        return $this->_value;
    }

    /**
     * JSON serialize.
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->_value;
    }

    /**
     * Convert the enum to a String value.
     *
     * @see https://www.php.net/manual/en/language.oop5.magic.php#object.tostring
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->_value;
    }

    /**
     * Sleep.
     *
     * @see https://www.php.net/manual/en/language.oop5.magic.php#object.sleep
     */
    public function __sleep()
    {
        throw new \LogicException('Enum is not serializable.');
    }

    /**
     * Wakeup.
     *
     * @see https://www.php.net/manual/en/language.oop5.magic.php#object.wakeup
     */
    public function __wakeup()
    {
        throw new \LogicException('Enum is not serializable.');
    }

    /**
     * Clone.
     *
     * @see https://www.php.net/manual/en/language.oop5.cloning.php#object.clone
     */
    public function __clone()
    {
        throw new \LogicException('Enum is not cloneable.');
    }

    /**
     * Get an enum instance by name.
     *
     * @param string|static $name Name or member instance.
     *
     * @throws \UnexpectedValueException on unknown enum member
     *
     * @return static
     */
    final public static function get($name)
    {
        // Called class name
        $calledClass = static::class;

        // If it's an enum instance, check it.
        if (is_object($name)) {
            if ($name instanceof $calledClass === false) {
                throw new \UnexpectedValueException(
                    sprintf('Instance is not an enum member of %s.', static::class)
                );
            }

            // Get actual name
            $name = $name->getName();

            $instance = self::$_instances[$calledClass][$name] ?? null;

            if ($instance !== null) {
                return $instance;
            }
        }

        // Prevent access to properties, constants and method prefixed with _
        if (strpos($name, '_') === 0) {
            throw new \UnexpectedValueException(
                sprintf('%s is not an enum member of %s.', $name, static::class)
            );
        }

        // Normalize name to upper snake_case
        $name = self::normalizeName($name);

        // Return cached instance.
        if (isset(self::$_instances[$calledClass][$name])) {
            return self::$_instances[$calledClass][$name];
        }

        // Reflection
        $ref = new \ReflectionClass($calledClass);

        // Convert casing
        $propName = static::convertPropertyCase($name);
        $constName = static::convertConstantCase($name);

        // Property or constant value
        $value = null;

        if ($ref->hasConstant($constName)) {
            $value = $ref->getConstant($constName);
        } elseif ($ref->hasProperty($propName)) {
            $prop = $ref->getProperty($propName);
            $prop->setAccessible(true);

            $value = $prop->getValue(new static(''));
        } elseif ($ref->hasMethod($propName)) {
            $method = $ref->getMethod($propName);
            $method->setAccessible(true);

            $value = $method->invoke(new static(''));
        } else {
            throw new \UnexpectedValueException(
                sprintf('%s is not an enum member of %s.', $name, $calledClass)
            );
        }

        if (isset(self::$_instances[$calledClass]) === false) {
            self::$_instances[$calledClass] = [];
        }

        // Create and store instance
        self::$_instances[$calledClass][$name] = new static($name, $value);

        return self::$_instances[$calledClass][$name];
    }

    /**
     * Check if enum has a member.
     *
     * @param string|static $name
     *
     * @return bool
     */
    final public static function has($name): bool
    {
        // Called class name
        $calledClass = static::class;

        // If it's an enum instance, check it.
        if (is_object($name)) {
            if ($name instanceof $calledClass === false) {
                return false;
            }

            // Get actual name
            $name = $name->getName();

            $instance = self::$_instances[$calledClass][$name] ?? null;

            if ($instance !== null) {
                return true;
            }
        }

        // Prevent access to properties, constants and method prefixed with _
        if (strpos($name, '_') === 0) {
            return false;
        }

        $ref = new \ReflectionClass($calledClass);

        // Normalize name to upper snake_case.
        $normalName = self::normalizeName($name);

        // Check if cached.
        if (isset(self::$_instances[$calledClass][$name])) {
            return true;
        }

        // Convert casing
        $methodName = str_replace('_', '', $name);
        $propName = static::convertPropertyCase($normalName);
        $constName = static::convertConstantCase($normalName);

        return $ref->hasConstant($constName)
            || $ref->hasProperty($propName)
            || $ref->hasMethod($methodName)
        ;
    }

    /**
     * Call static.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @throws \BadMethodCallException on unknown enum member
     *
     * @see https://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic
     *
     * @return static
     */
    final public static function __callStatic($name, $arguments)
    {
        try {
            return static::get($name);
        } catch (\UnexpectedValueException $ex) {
            throw new \BadMethodCallException($ex->getMessage(), 0, $ex);
        }
    }

    /**
     * Convert the enum name to property name.
     *
     * By default this will convert upper snake_case (e.g. FOO_BAR) to camelCase (e.g. fooBar).
     *
     * Override this method if you prefer other casing like snake_case.
     *
     * @param string $name The enum name (e.g. FOO_BAR)
     *
     * @return string the property name
     */
    protected static function convertPropertyCase(string $name): string
    {
        $parts = explode('_', strtolower($name), 2);

        if (count($parts) === 1) {
            return $parts[0];
        }

        return $parts[0].str_replace(' ', '', ucwords(str_replace('_', ' ', $parts[1])));
    }

    /**
     * Convert the enum name to constant name.
     *
     * By default this will convert everything to uppercase.
     *
     * @param string $name The enum name (e.g. foo_bar)
     *
     * @return string the constant name
     */
    protected static function convertConstantCase(string $name): string
    {
        return strtoupper($name);
    }

    /**
     * Normalize name.
     *
     * @param string $name
     *
     * @return string name in upper snake_case
     */
    private static function normalizeName(string $name): string
    {
        $name = str_replace(' ', '_', $name);

        if (strpos($name, '_') === false && preg_match('/[A-Z]{2,}/', $name) !== 1) {
            $name = preg_replace('/(?<!^)([A-Z])/', '_$1', $name);
        }

        return strtoupper($name);
    }
}
