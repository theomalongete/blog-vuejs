<?php

namespace Database\Factories\Post;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\User;

use Faker\Generator as Faker;
use Faker\Factory as Custom;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $Users = User::all()->pluck('id')->toArray();
        $user_id = $this->faker->randomElement($Users);
        $num = $this->faker->randomElement(array('0','1','2'));
        $arr = $this->faker->sentences();
        if($num == 0)$arr = $this->faker->paragraphs();
        if($num == 1)$arr = $this->faker->paragraphs(1);
        if($num == 2)$arr = $this->faker->paragraphs(2);
        $paragraphs = null;
        for ($i=0; $i < count($arr); $i++) { 
            $paragraphs .= $arr[$i];
        }

        return [
            'post_title' => $this->faker->word(),
            'post_content' => $paragraphs,
            'user_id' => $user_id
        ];
    }
}
