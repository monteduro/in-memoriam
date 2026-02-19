<?php

namespace App\Support;

use Filament\Forms\Components\Component;
use InvalidArgumentException;

class FilamentFieldFactory
{
    public static function make(array $definition): Component
    {
        $class = $definition['class'] ?? null;
        $name = $definition['make'] ?? null;

        if (!is_string($class) || $class === '') {
            throw new InvalidArgumentException('Field definition is missing a valid "class".');
        }

        if (!is_string($name) || $name === '') {
            throw new InvalidArgumentException('Field definition is missing a valid "make" value.');
        }

        if (!class_exists($class)) {
            throw new InvalidArgumentException("Field class [{$class}] does not exist.");
        }

        if (!method_exists($class, 'make')) {
            throw new InvalidArgumentException("Field class [{$class}] does not support static make().");
        }

        $field = $class::make($name);
        $methods = $definition['methods'] ?? [];

        if (!is_array($methods)) {
            throw new InvalidArgumentException('Field definition "methods" must be an array.');
        }

        foreach ($methods as $method => $value) {
            if (!is_string($method) || $method === '') {
                throw new InvalidArgumentException('Field method names must be non-empty strings.');
            }

            if (!method_exists($field, $method)) {
                throw new InvalidArgumentException("Method [{$method}] is not available on [{$class}].");
            }

            if ($value === true) {
                $result = $field->{$method}();
            } elseif ($value === false || $value === null) {
                continue;
            } elseif (is_array($value) && array_is_list($value)) {
                $result = $field->{$method}(...$value);
            } else {
                $result = $field->{$method}($value);
            }

            if ($result instanceof Component) {
                $field = $result;
            }
        }

        return $field;
    }
}
