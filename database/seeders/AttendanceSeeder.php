<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Genera 50 registros de asistencia para el usuario con ID 1
        Attendance::factory()->count(100)->create();
    }
}
