<?php

namespace Database\Seeders;

use App\Models\BusinessHour;
use Illuminate\Database\Seeder;

class BusinessHourSeeder extends Seeder
{
    public function run(): void
    {
        for ($day = 0; $day < 7; $day++) {
            BusinessHour::firstOrCreate(['day_order' => $day], [
                'is_open' => true,
                'shift1_start' => '11:00',
                'shift1_end' => '16:00',
                'shift2_start' => '18:45',
                'shift2_end' => '23:00',
            ]);
        }
    }
}
