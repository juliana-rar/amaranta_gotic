<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiteContentSeeder::class,
            SiteTextSeeder::class,
            DishSeeder::class,
            GallerySeeder::class,
            TestimonialSeeder::class,
            BusinessHourSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
