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
            RoleAndPermissionSeeder::class,
            LicenseSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Director',
            'surname' => 'Test Director',
            'dni' => '12345677',
            'phone' => '12345678',
            'email' => 'd@gmail.com',
            'password' => Hash::make('contraseña'),
        ]);

        User::factory()->create([
            'name' => 'Supervisor',
            'surname' => 'Test Supervisor',
            'dni' => '12345678',
            'phone' => '12345678',
            'email' => 's@gmail.com',
            'password' => Hash::make('contraseña'),
        ]);

        User::factory()->create([
            'name' => 'Operario',
            'surname' => 'Test Operario',
            'dni' => '12345679',
            'phone' => '12345678',
            'email' => 'o@gmail.com',
            'password' => Hash::make('contraseña'),
        ]);


        //Asignar rol de director al usuario creado
        $user = User::find(1);
        $user->assignRole('Director');
        $user = User::find(2);
        $user->assignRole('Supervisor');
        $user = User::find(3);
        $user->assignRole('Operario');

        $this->call(AttendanceSeeder::class);

        //LicenseSeeder


    }
}
