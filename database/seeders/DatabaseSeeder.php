<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*// User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $this->call([
            RoleAndPermissionSeeder::class
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'surname' => 'Test User',
            'dni' => '12345678',
            'phone' => '12345678',
            'email' => 'e@gmail.com',
            'password' => Hash::make('contraseña'),
        ]);

        //Asignar rol de director al usuario creado
        $user = User::find(1);
        $user->assignRole('Director');

    }
}
