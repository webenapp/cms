<?php

namespace Statamic\GraphQL\Types;

use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Support\Str;

class Entry extends ObjectType
{
    public static function name(array $args): string
    {
        [$collection, $blueprint] = $args;

        return 'Entry_' . Str::studly($collection->handle()) . '_' . Str::studly($blueprint->handle());
    }

    public function config(array $args): array
    {
        [$collection, $blueprint] = $args;

        return [
            'interfaces' => [
                $entry = Type::get(EntryInterface::class, $args),
            ],
            'fields' => function () use ($entry, $blueprint) {
                $scope = [$blueprint->handle()];

                return collect($entry->fields())
                    ->merge($blueprint->fields()->toGraphQL(compact('blueprint', 'scope')))
                    ->all();
            },
            'resolveField' => function (EntryContract $entry, $args, $context, $info) {
                return $entry->augmentedValue($info->fieldName);
            }
        ];
    }
}
