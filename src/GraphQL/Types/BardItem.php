<?php

namespace Statamic\GraphQL\Types;

use Statamic\Support\Str;

class BardItem extends UnionType
{
    public static function name(array $args): string
    {
        return 'BardItem_' . collect($args['scope'])->map(function ($item) {
            return Str::studly($item);
        })->join('_');
    }

    public function config(array $args): array
    {
        return [
            'types' => $this->types($args),
            'resolveType' => function ($item) use ($args) {
                if ($item['type'] === 'text') {
                    return Type::get(BardText::class);
                }

                $args['scope'][] = $item['type'];

                return Type::get(Set::class, [
                    'bard' => $args['fieldtype'],
                    'handle' => $item['type'],
                    'blueprint' => $args['blueprint'],
                    'scope' => $args['scope'],
                ]);
            }
        ];
    }

    protected function types(array $args): array
    {
        $bard = $args['fieldtype'];
        $blueprint = $args['blueprint'];
        $scope = $args['scope'];

        return collect($bard->config('sets'))
            ->map(function ($set, $handle) use ($bard, $blueprint, $scope) {
                $scope[] = $handle;
                return Type::get(Set::class, compact('bard', 'handle', 'blueprint', 'scope'));
            })
            ->put('__text', Type::get(BardText::class))
            ->all();
    }
}
