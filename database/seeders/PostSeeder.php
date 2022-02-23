<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post\Post;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory(200)->create();

        $this->command->info('Posts added.');
    }
}
