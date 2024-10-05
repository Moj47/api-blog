<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
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
    public function testShowAPostWithShowMethod()
    {
        $post=Post::factory()->create();
        $user=User::factory()->create();
        $response = $this->actingAs($user)
        ->getJson(route('posts.show',$post->id));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'title',
                'text',
                'image',
                'slug',
                'category_id',
            ],
        ]);
    }
    public function testCreateAPostWithStoreMethod()
{
    $post = Post::factory()->make();
    $user = User::factory()->create(['type' => 'admin']);

    // Create a fake image file
    $image = UploadedFile::fake()->image('test_image.jpg', 400, 400);

    // Add the image to the post data
    $postData = $post->toArray();
    $postData['image'] = $image;

    $response = $this->actingAs($user)
        ->postJson(route('posts.store'), $postData);

    // dd($response);
    $response->assertStatus(201);

    $response->assertJsonStructure([
        'data' => [
            'title',
            'text',
            'image',
            'slug',
            'category_id',
        ],
    ]);
   $this->assertDatabaseHas('posts',[
    'title'=>$post->title,
    'text'=>$post->text,
    'category_id'=>$post->category_id
   ]);
}
}
