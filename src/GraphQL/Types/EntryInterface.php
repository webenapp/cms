<?php

namespace Statamic\GraphQL\Types;

use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Collection;
use Statamic\GraphQL\Types\Collection as CollectionType;
use Statamic\GraphQL\Types\Entry as EntryType;
use Statamic\GraphQL\Types\InterfaceType;
use Statamic\GraphQL\Types\Type;

class EntryInterface extends InterfaceType
{
    public static function name(array $args): string
    {
        return 'EntryInterface';
    }

    public function config(array $args): array
    {
        return [
            'description' => 'An entry within a collection',
            'fields' => $this->fields(),
            'resolveType' => function (EntryContract $entry) {
                return Type::get(EntryType::class, [$entry->collection(), $entry->blueprint()]);
            }
        ];
    }

    public function fields(): array
    {
        return [
            'id' => Type::nonNull(Type::ID()),
            'title' => Type::nonNull(Type::string()),
            'slug' => Type::nonNull(Type::string()),
            'uri' => Type::string(),
            'url' => Type::string(),
            'permalink' => Type::string(),
            'published' => Type::boolean(),
            'private' => Type::boolean(),
            'date' => Type::string(),
            'collection' => Type::get(CollectionType::class),
        ];
    }

    public static function types(): array
    {
        return Collection::all()
            ->flatMap(function ($collection) {
                return $collection->entryBlueprints()->map(function ($blueprint) use ($collection) {
                    return compact('collection', 'blueprint');
                });
            })
            ->mapWithKeys(function ($item, $handle) {
                $type = Type::getByCollection($item['collection'], $item['blueprint']);
                return [$type->name => $type];
            })
            ->all();
    }
}
