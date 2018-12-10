<?php

namespace Tests\Feature\Blueprints;

use Mockery;
use Statamic\API;
use Tests\TestCase;
use Tests\FakesRoles;
use Statamic\Auth\User;
use Statamic\Fields\Blueprint;
use Statamic\Data\Entries\Collection;

class ViewBlueprintListingTest extends TestCase
{
    use FakesRoles;

    /** @test */
    function it_shows_a_list_of_fieldsets()
    {
        API\Blueprint::shouldReceive('all')->andReturn(collect([
            'foo' => $blueprintA = $this->createBlueprint('foo'),
            'bar' => $blueprintB = $this->createBlueprint('bar')
        ]));

        $user = API\User::make()->makeSuper();

        $response = $this
            ->actingAs($user)
            ->get(cp_route('blueprints.index'))
            ->assertSuccessful()
            ->assertViewHas('blueprints', collect([
                [
                    'id' => 'foo',
                    'handle' => 'foo',
                    'title' => 'Foo',
                    'sections' => 0,
                    'fields' => 0,
                    'edit_url' => 'http://localhost/cp/blueprints/foo/edit'
                ],
                [
                    'id' => 'bar',
                    'handle' => 'bar',
                    'title' => 'Bar',
                    'sections' => 0,
                    'fields' => 0,
                    'edit_url' => 'http://localhost/cp/blueprints/bar/edit'
                ],
            ]))
            ->assertDontSee('no-results');
    }

    /** @test */
    function it_denies_access_if_you_dont_have_permission()
    {
        $this->setTestRoles(['test' => ['access cp']]);
        $user = API\User::make()->assignRole('test');

        $response = $this
            ->from('/cp/original')
            ->actingAs($user)
            ->get(cp_route('blueprints.index'))
            ->assertRedirect('/cp/original');
    }

    private function createBlueprint($handle)
    {
        return tap(new Blueprint)->setHandle($handle);
    }
}
