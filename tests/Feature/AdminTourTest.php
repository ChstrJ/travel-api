<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTourTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_public_user_cannot_access_adding_tour()
    {
        $travel = Travel::factory()->create();

        $response = $this->postJson("/api/v1/admin/travels/{$travel->id}/tours");

        $response->assertStatus(401);
    }

    public function test_editor_cannnot_access_adding_tour()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();

        $roleId = Role::where('name', 'editor')->first()->id;

        $user->roles()->attach($roleId);

        $travel = Travel::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/v1/admin/travels/{$travel->id}/tours");

        $response->assertStatus(403);
    }

    public function test_saves_tour_successfully_with_valid_data()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();

        $roleId = Role::where('name', 'admin')->first()->id;

        $user->roles()->attach($roleId);

        $travel = Travel::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/v1/admin/travels/{$travel->id}/tours", [
            'name' => 'tour name',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson("/api/v1/admin/travels/{$travel->id}/tours", [
            'name' => 'tour name',
            'starting_date' => now()->toDateString(),
            'ending_date' => now()->addDay()->toDateString(),
            'price' => '42.45'
        ]);

        $response->assertStatus(201);

        $response = $this->get("/api/v1/admin/travels/{$travel->id}/tours");
        $response->assertJsonFragment(['name' => 'tour name']);
    }
    
}
