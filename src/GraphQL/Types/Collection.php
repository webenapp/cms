<?php

namespace Statamic\GraphQL\Types;

use Statamic\Support\Str;

class Collection extends ObjectType
{
    public static function name(array $args): string
    {
        return 'Collection';
    }

    public function config(array $args): array
    {
        return [
            'fields' => [
                'title' => Type::nonNull(Type::string()),
                'handle' => Type::nonNull(Type::string()),
            ],
            'resolveField' => function ($collection, $args, $context, $info) {
                return $collection->{$info->fieldName}();
            }
        ];
    }
}
