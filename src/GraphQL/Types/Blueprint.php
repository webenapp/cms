<?php

namespace Statamic\GraphQL\Types;

use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\GraphQL\Types\ObjectType;

class Blueprint extends ObjectType
{
    public static function name(array $args): string
    {
        [$blueprint] = $args;

        return 'Blueprint_' . $blueprint->handle();
    }

    public function config(array $args): array
    {
        dd('blueprint config', $args);
        [$collection, $blueprint] = $args;

        return [
            'interfaces' => [
                $entry = Type::get(EntryInterface::class, $args),
                $blueprint = Type::get(BlueprintInterface::class, [$blueprint]),
            ],
            'fields' => function () use ($entry) {
                return array_merge($entry->fields(), $blueprint->fields());
            },
            'resolveField' => function (EntryContract $entry, $args, $context, $info) {
                return $entry->augmentedValue($info->fieldName);
            }
        ];
    }
}
