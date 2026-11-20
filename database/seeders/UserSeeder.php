<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Администратор
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@petshop.ru',
            'password' => bcrypt('admin12345'),
            'role' => 'admin',
            'phone' => '+7 (999) 000-00-00',
        ]);

        // Обычный пользователь
        User::create([
            'name' => 'Иванов Иван Иванович',
            'email' => 'user@petshop.ru',
            'password' => bcrypt('user12345'),
            'role' => 'user',
            'phone' => '+7 (999) 123-45-67',
        ]);
    }
}
