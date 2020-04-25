<?php

namespace Statamic\GraphQL\Types;

class BardText extends ObjectType
{
    public static function name(array $args): string
    {
        return 'BardText';
    }

    public function config(array $args): array
    {
        return [
            'fields' => [
                'text' => Type::string()
            ]
        ];
    }
}
