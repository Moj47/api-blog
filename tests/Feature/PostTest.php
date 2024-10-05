<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testShowAllPostsWithIndexMethod()
    {

      Post::factory(3)->create();

        $user=User::factory()->create();
        $response = $this->actingAs($user)
        ->getJson(route('posts.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'title',
                    'text',
                    'image',
                    'slug',
                    'category_id',
                ],
            ],
        ]);

    }

}
