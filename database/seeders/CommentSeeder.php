<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::factory(500)->create();

        $this->command->info('Comments added.');
    }
}
