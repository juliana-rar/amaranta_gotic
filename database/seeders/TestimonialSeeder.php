<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['Mesa de doce', 'Juntaron las mesas en un rincón precioso del Gótico. Los arroces y la paletilla a la brasa, espectaculares.'],
            ['Equipo de oficina', 'Cena de empresa en pleno centro, con música suave y servicio atento. Repetimos arroz meloso dos veces.'],
            ['Paseantes del barrio', 'Un local con carácter, luz baja y olor a brasa nada más entrar. Justo lo que buscábamos tras pasear por la Catedral.'],
        ];

        foreach ($testimonials as $index => [$author, $quote]) {
            Testimonial::firstOrCreate(['author_name' => $author], [
                'quote' => $quote,
                'display_order' => $index + 1,
            ]);
        }
    }
}
