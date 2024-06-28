<?php

namespace Tests\Feature;

use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTravelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_public_user_cannot_access_adding_travel()
    {
        $response = $this->postJson('api/v1/admin/travels');

        $response->assertStatus(401);
    }

    public function test_non_admin_cannot_access_adding_travel()
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();

        $roleId = Role::where('name', 'editor')->first()->id;

        $user->roles()->attach($roleId);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels');

        $response->assertStatus(403);
    }

    public function test_saves_travel_successfully_with_valid_data()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $roleId = Role::where('name', 'admin')->first()->id;

        $user->roles()->attach($roleId);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'Travel name'
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/travels', [
            'name' => 'Travel name',
            'is_public' => 1,
            'description' => 'test test',
            'number_of_days' => 5
        ]);

        $response->assertStatus(201);

        $response = $this->get('api/v1/travels');
        $response->assertJsonFragment(['name' => 'Travel name']);
    }
}
