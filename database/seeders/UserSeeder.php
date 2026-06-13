<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@itsm.local'],
            [
                'name' => 'Admin',
                'email' => 'admin@itsm.ci',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );

        User::factory(5)->create();
    }
}