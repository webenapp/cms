<?php

namespace Statamic\Http\Controllers;

use GraphQL\Error\Debug;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Illuminate\Http\Request;
use Statamic\GraphQL\Types\Type;

class GraphQLController
{
    public function index(Request $request)
    {
        $schema = $this->schema();

        try {
            $schema->assertValid();
        } catch (\GraphQL\Error\InvariantViolation $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

       $result = GraphQL::executeQuery($schema, $request->input('query'), null, null, $request->input('variables'));

       $flags = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;

        return $result->toArray($flags);
    }

    protected function schema()
    {
        return new Schema([
            'query' => Type::query(),
            'types' => Type::registered(),
            'typeLoader' => function ($name) {
                return Type::getByTypeName($name);
            }
        ]);
    }
}
