<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();
        // $this->command->call('migrate:refresh');
        // $this->command->warn("Data cleared, starting from blank database.");
        Model::unguard();
        $this->command->info("=================================");
        Model::unguard();

        //User
        $this->call(UserSeeder::class);
        //Post
        $this->call(PostSeeder::class);
        
        Model::reguard();
        $this->command->warn('All done :)');
    }
}
