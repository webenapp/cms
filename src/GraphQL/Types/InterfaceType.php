<?php

namespace Statamic\GraphQL\Types;

use GraphQL\Type\Definition\InterfaceType as BaseInterfaceType;

abstract class InterfaceType extends BaseInterfaceType
{
    public function __construct(array $args)
    {
        // todo: maybe the interface constructor doesnt accept args.
        parent::__construct(array_merge([
            'name' => static::name($args),
        ], $this->config($args)));
    }

    abstract public function config(array $args): array;
    abstract public static function name(array $args): string;

    public static function registerTypes()
    {
        foreach (static::types() as $name => $type) {
            Type::register($name, $type);
        }
    }

    public static function types(): array
    {
        return [];
    }
}
