<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelListTest extends TestCase
{
    use RefreshDatabase;
    public function testTravelsListReturnPaginatedDataCorrectly(): void
    {
        //create fake data
        Travel::factory(16)->create(['is_public' => true]);

        //route
        $response = $this->get('/api/v1/travels');

        //assert
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function testCreateTravel()
    {
        $data = Travel::factory(1)->create(['is_public' => true]);

    }

    public function testTravelsListShowsOnlyPublicRecords(): void
    {
        //create fake data
        $public = Travel::factory()->create(['is_public' => true]);
        Travel::factory()->create(['is_public' => false]);

        //asses
        $response = $this->get('/api/v1/travels');

        //assert
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', $public->name);
    }
}
