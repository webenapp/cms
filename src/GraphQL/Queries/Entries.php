<?php

namespace Statamic\GraphQL\Queries;

use Statamic\Facades\Entry;
use Statamic\GraphQL\Types\EntryInterface;
use Statamic\GraphQL\Types\Type;

class Entries
{
    public static function definition(): array
    {
        EntryInterface::registerTypes();

        return [
            'type' => Type::listOf(Type::get(EntryInterface::class)),
            'args' => [
                'collection' => Type::listOf(Type::string()),
            ],
            'resolve' => function ($value, $args) {
                $query = Entry::query();

                if ($collection = $args['collection'] ?? null) {
                    $query->whereIn('collection', $collection);
                }

                return $query->get()->all();
            }
        ];
    }
}
