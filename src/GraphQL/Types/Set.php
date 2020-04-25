<?php

namespace Statamic\GraphQL\Types;

use Statamic\Support\Str;

class Set extends ObjectType
{
    public static function name(array $args): string
    {
        return 'Set_' . collect($args['scope'])->map(function ($item) {
            return Str::studly($item);
        })->join('_');
    }

    public function config(array $args): array
    {
        return [
            'fields' => function () use ($args) {
                return $args['bard']->fields($args['handle'])->toGraphQL($args)->all();
            }
        ];
    }
}
