<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Buat 10 data dummy user
        User::factory(10)->create();

        // Buat admin
        User::factory()->create([
            'name_212102' => 'fifi',
            'email_212102' => 'fifi@gmail.com',
            'telephone_212102' => '081234567890',
            'role_212102' => 'admin',
            'password_212102' => bcrypt('admin123'),
        ]);

        // Buat user biasa
        User::factory()->create([
            'name_212102' => 'mell',
            'email_212102' => 'mell@gmail.com',
            'telephone_212102' => '081234567890',
            'role_212102' => 'customer',
            'password_212102' => bcrypt('user123'),
        ]);
    }
}
