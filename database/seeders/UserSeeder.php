<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ==================== ADMIN ====================
        User::create([
            'nama' => 'Admin',
            'alamat' => 'Jakarta',
            'email' => 'admin@example.com',
            'image' => 'profil-pic/default.jpg',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'is_active' => 1,
        ]);

        User::create([
            'nama' => 'Vickry Kamaluddin',
            'alamat' => 'Nusa Mandiri, Jakarta',
            'email' => 'vickry@gmail.com',
            'image' => 'profil-pic/default.jpg',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'is_active' => 1,
        ]);

        // ==================== 50 MEMBER ====================
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'image' => 'profil-pic/default.jpg',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'is_active' => 1,
            ]);
        }

        $this->command->info('✅ Users created: 1 admin + 50 members');
    }
}