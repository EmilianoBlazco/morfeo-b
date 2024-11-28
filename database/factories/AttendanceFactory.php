<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['Presente', 'Ausente', 'Tardanza'];
        $status = $this->faker->randomElement($statuses);
        $date = $this->faker->dateTimeBetween('-90 days', 'now');

        $entryTime = null;
        $exitTime = null;

        if ($status === 'Presente') {
            $entryTime = Carbon::instance($date)->setTime(rand(7, 9), rand(0, 59));
            $exitTime = (clone $entryTime)->addHours(8)->addMinutes(rand(0, 59));
        } elseif ($status === 'Tardanza') {
            $entryTime = Carbon::instance($date)->setTime(rand(10, 12), rand(0, 59));
            $exitTime = (clone $entryTime)->addHours(8)->addMinutes(rand(0, 59));
        }

        return [
            'user_id' => 1, // ID del usuario específico para generar registros
            'entry_time' => $entryTime,
            'exit_time' => $exitTime,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
