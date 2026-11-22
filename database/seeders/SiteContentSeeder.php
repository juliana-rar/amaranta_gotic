<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        SiteContent::updateOrCreate(['id' => 1], [
            'hero_eyebrow' => '🔥 Brasería mediterránea en el Barri Gòtic',
            'hero_title' => 'Fuego, piedra y una mesa larga en el Gótico',
            'hero_subtitle' => 'Carnes a la brasa, arroces para compartir y copas bajo la luz baja de la Baixada de Sant Miquel. A un paso de la Catedral de Barcelona.',

            'stat1_number' => '100%', 'stat1_label' => 'Cocina a la brasa',
            'stat2_number' => '24 €', 'stat2_label' => 'Menús de grupo desde',
            'stat3_number' => '50', 'stat3_label' => 'Personas en eventos',
            'stat4_number' => '08002', 'stat4_label' => 'Barri Gòtic, Barcelona',

            'feature1_icon' => '🔥', 'feature1_title' => 'La brasa manda',
            'feature1_description' => 'Carnes, pescados y verduras marcados por el fuego, con producto fresco y técnica visible.',
            'feature2_icon' => '🍚', 'feature2_title' => 'Para compartir',
            'feature2_description' => 'Arroces melosos y fideuás que se piden en el centro de la mesa y se reparten entre todos.',
            'feature3_icon' => '🕯️', 'feature3_title' => 'Gótico de noche',
            'feature3_description' => 'Piedra, luz baja y callejones estrechos a dos pasos de la Catedral y la Plaça Reial.',

            'category1_image' => '/images/amaranta-brasa.jpg',
            'category1_title' => 'De la brasa',
            'category1_description' => 'Carrilleras, paletilla de cordero y entrecot madurado marcados por el fuego.',
            'category2_image' => '/images/amaranta-mesa.jpg',
            'category2_title' => 'Arroces',
            'category2_description' => 'Paellas y arroces melosos de pulpo y langostinos para compartir.',
            'category3_image' => '/images/amaranta-ambiente.jpg',
            'category3_title' => 'Tapas',
            'category3_description' => 'Gambas al ajillo, tataki de atún y patatas amarantas para ir picando.',
            'category4_image' => '/images/amaranta-ambiente.jpg',
            'category4_title' => 'Copas',
            'category4_description' => 'Coctelería, vino de la casa y sobremesa larga en el Gótico.',

            'story_eyebrow' => 'Nuestra historia',
            'story_title' => 'Una callejuela, una brasa y una mesa larga',
            'story_paragraph1' => 'Amaranta Gótico nació en la Baixada de Sant Miquel, entre piedras antiguas y el rumor de la Catedral. Aquí el fuego marca el ritmo: carnes a la brasa, arroces que se comparten y tapas que invitan a quedarse.',
            'story_paragraph2' => 'Somos un local de barrio con alma bohemia: luz baja, paredes de piedra, copas bien servidas y sitio para todos. Venga solo, en pareja o con veinte personas: la mesa se hace grande cuando hace falta.',
            'story_quote' => 'El fuego no tiene prisa, y aquí tampoco.',
            'story_quote_author' => 'Amaranta Gótico',

            'location_address' => "Baixada de Sant Miquel, 6\n08002 Barcelona",
            'location_hours' => 'Todos los días: 11:00–16:00 y 18:45–23:00',
            'contact_phone' => '+34 644 260 818',
            'contact_email' => 'amarantagotico@gmail.com',
            'contact_instagram' => '@amarantagotico',
        ]);
    }
}
