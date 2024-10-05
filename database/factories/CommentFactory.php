<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user=User::factory()->create();
        $category=Category::factory()->create();
        $post=Post::factory()->create();
        return [
            'title'=>$this->faker->word,
            'text'=>$this->faker->sentence,
            'user_id'=>$user->id,
            'category_id'=>$category->id,
            'post_id'=>$post->id
        ];
    }
}
