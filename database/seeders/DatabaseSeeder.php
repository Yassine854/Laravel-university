<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        User::create(['name' => 'admin','last_name' => 'admin','email' => 'admin@test.com','password' => Hash::make("admin@test.com"),'role_id' => 1,]);
    }
}
