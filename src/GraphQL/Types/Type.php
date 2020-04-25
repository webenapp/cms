<?php

namespace Statamic\GraphQL\Types;

use GraphQL\Type\Definition\Type as BaseType;
use Statamic\GraphQL\Types\Entry;

class Type extends BaseType
{
    protected static $types = [];

    public static function register($name, $type)
    {
        static::$types[$name] = $type;
    }

    public static function registered()
    {
        return static::$types;
    }

    public static function get(string $name, array $args = [])
    {
        return static::getByClass($name, $args);
    }

    public static function getByTypeName($typeName)
    {
        $cacheName = $typeName;
        $type = null;

        if (isset(self::$types[$cacheName])) {
            return self::$types[$cacheName];
        }

        $method = lcfirst($typeName);

        if (method_exists(get_called_class(), $method)) {
            $type = self::{$method}();
        }

        dump($typeName, static::$types);

        throw_unless($type, new \Exception("Unknown GraphQL type: " . $typeName));

        return $type;
    }

    protected static function getByClass(string $class, array $args = [])
    {
        $name = $class::name($args);
        $type = null;

        if (! isset(static::$types[$name])) {
            if (class_exists($class)) {
                $type = new $class($args);
            }

            static::$types[$name] = $type;
        }

        $type = static::$types[$name];

        if (!$type) {
            throw new \Exception("Unknown graphql type: " . $class);
        }

        return $type;
    }

    public static function getByCollection($collection, $blueprint = null)
    {
        if ($blueprint instanceof \Statamic\Fields\Blueprint) {
            return self::get(Entry::class, [$collection, $blueprint]);
        }

        $blueprints = $collection->entryBlueprints();

        return $blueprints->count() === 1
            ? self::get(Entry::class, [$collection, $blueprints->first()])
            : self::get(UnionEntry::class, [$collection, $blueprints]);
    }

    public static function query()
    {
        return static::get(Query::class);
    }
}
