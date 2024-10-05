<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $Category=Category::factory()->create();
        return [
            'title'=>$this->faker->word,
            'text'=>$this->faker->sentence,
            'image'=>$this->faker->image,
            'slug'=>$this->faker->word.Str::random(4),
            'category_id'=>$Category->id
        ];
    }
}
