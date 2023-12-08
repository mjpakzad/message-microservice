<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'first_name' => 'Mojtaba',
            'last_name' => 'Pakzad',
            'mobile' => '09123456789',
            'mobile_verified_at' => now(),
            'password' => bcrypt('D3@f#m*c6'),
        ]);
    }
}
