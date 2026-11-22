<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            ['/images/amaranta-ambiente.jpg', 'El ambiente del local, entre piedras y luz baja'],
            ['/images/amaranta-brasa.jpg', 'La brasa encendida, el corazón de la cocina'],
            ['/images/amaranta-mesa.jpg', 'Mesa larga para compartir arroces y brasas'],
        ];

        foreach ($images as $index => [$url, $caption]) {
            GalleryImage::firstOrCreate(['image_url' => $url], [
                'caption' => $caption,
                'display_order' => $index + 1,
            ]);
        }
    }
}
