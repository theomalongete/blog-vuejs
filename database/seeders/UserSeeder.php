<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = bcrypt("123456");
        $user = User::where('email','testthe23232o@gmail.com')->first();
        if(!$user){
            User::create([
                'user_first_name' => "Theo",
                'user_surname' => "Engineer",
                'email' => "testthe23232o@gmail.com",
                'email_verified_at' => now(),
                'username' => "theo",
                'password' => $password
            ]);
        }
        User::factory(50)->create();

        $this->command->info('Users added.');
    }
}
