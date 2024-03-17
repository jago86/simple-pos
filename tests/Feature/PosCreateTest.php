<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PosCreateTest extends TestCase
{
    /** @test */
    public function can_render_sale_creation_page()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get(route('pos.create'))
            ->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Pos/Create')
        );
    }
}
