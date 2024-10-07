<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testSeeAllCategoriesWithIndexMethod(): void
    {
        $user=User::factory()->create();
        Category::factory(5)->create();
        $response = $this->actingAs($user)
        ->get(route('categories.index'));
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
        $user=User::factory()->create();
        $category=Category::factory()->create();

        $response=$this->actingAs($user)
        ->get(route('categories.show',$category->id));

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);
    }
    public function testCreateACategoryWithStoreMethod()
    {
        $category=Category::factory()->make();
        $user=User::factory()->create(['type'=>'admin']);
        $response=$this->actingAs($user)
        ->post(route('categories.store',$category->toArray()));
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);        $this->assertDatabaseHas('categories',$category->toArray());
    }
}
