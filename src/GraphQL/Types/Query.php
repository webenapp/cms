<?php

namespace Statamic\GraphQL\Types;

use Statamic\GraphQL\Queries\Entries;

class Query extends ObjectType
{
    public static function name(array $args): string
    {
        return 'Query';
    }

    public function config(array $args): array
    {
        return [
            'name' => 'Query',
            'fields' => [
                'ping' => [
                    'type' => Type::string(),
                    'resolve' => function() {
                        return 'pong';
                    },
                ],
                'entries' => Entries::definition()
            ],
        ];
    }
}
