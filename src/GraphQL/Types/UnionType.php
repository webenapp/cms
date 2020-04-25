<?php

namespace Statamic\GraphQL\Types;

use GraphQL\Type\Definition\UnionType as BaseUnionType;

abstract class UnionType extends BaseUnionType
{
    public function __construct(array $args)
    {
        parent::__construct(array_merge([
            'name' => static::name($args),
        ], $this->config($args)));
    }

    abstract public function config(array $args): array;
    abstract public static function name(array $args): string;
}
