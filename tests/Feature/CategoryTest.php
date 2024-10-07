<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testSeeAllCategoriesWithIndexMethod(): void
    {
        $user = User::factory()->create();
        Category::factory(5)->create();
        $response = $this->actingAs($user)
            ->getJson(route('categories.index'));
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                ],
            ],
        ]);
    }
    public function testShowACategoryWithShowMethod()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('categories.show', $category->id));

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);
    }
    public function testCreateACategoryWithStoreMethod()
    {
        $category = Category::factory()->make();
        $user = User::factory()->create(['type' => 'admin']);
        $response = $this->actingAs($user)
            ->postJson(route('categories.store', $category->toArray()));
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);
        $this->assertDatabaseHas('categories', $category->toArray());
    }
    public function testUpdateACategoryWithUpdateMethod()
    {
        $category = Category::factory()->create();
        $data = Category::factory()->make();

        $user = User::factory()->create(['type' => 'admin']);
        $response = $this->actingAs($user)
            ->putJson(route('categories.update', $category->id), $data->toArray());
        $this->assertDatabaseHas('categories', $data->toArray());

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);
    }
    public function testDeleteATestCategoryWithDestroyMethod()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create(['type' => 'admin']);

        $this->assertDatabaseHas('categories', $category->toArray());
        $response = $this->actingAs($user)
            ->deleteJson(route('categories.destroy', $category->id));

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);

        $this->assertDatabaseMissing('categories', $category->toArray());
    }
    public function testSeePostsOfACategoryWithRelation()
    {
        $user=User::factory()->create();
        $category = Category::factory()->create();
        $posts = Post::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)
        ->getJson(route('categories.posts', $category->id));

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'posts' => [
                    '*' => [
                        'title',
                        'text',
                        'image',
                        'slug',
                        'category_id',
                    ],
                ],
            ],
        ]);
    }
    public function testSeeCommentsOfACategoryWithRelation()
    {
        $user=User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);
        $comments = Comment::factory()->count(5)->create(['category_id' => $category->id,'post_id'=>$post->id]);

        $response = $this->actingAs($user)
        ->getJson(route('categories.comments', $category->id));
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'comments' => [
                    '*' => [
                        'title',
                        'text',
                        'user_id',
                        'post_id',
                    ],
                ],
            ],
        ]);
    }
}
