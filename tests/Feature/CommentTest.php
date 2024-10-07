<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateACommentWithStoreMethod()
    {
        $post = Post::factory()->create();
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $data = [
            'title' => 'test',
            'text' => 'sdfdsfdsfdsfdsf',
            'user_id' => $user->id,
            'post_id' => $post->id,
            'category_id' => $category->id
        ];
        $response = $this->actingAs($user)
            ->postJson(route('comments.create'), $data);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'title',
                'text',
                'user_id',
                'post_id',
            ],
        ]);
        $this->assertDatabaseHas('comments', $data);
    }
    public function testDeleteAcommentWithDestroyMethod()
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);
        $this->assertDatabaseHas('comments', $comment->toArray());
        $response = $this->actingAs($user)
            ->delete(route('comments.delete', $comment->id));
        $this->assertDatabaseMissing('comments', $comment->toArray());
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'title',
                'text',
                'user_id',
                'post_id',
            ],
        ]);
    }
}
