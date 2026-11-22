<?php

namespace Database\Seeders;

use App\Models\Dish;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $dishes = [
            ['Carrilleras al vino tinto', 'Cocidas a fuego lento con vino tinto y especias.', '', 'De la brasa', '18,50 €', false],
            ['Paletilla de cordero a la brasa', 'Cordero marcado por el fuego, tierno y jugoso.', '/images/amaranta-brasa.jpg', 'De la brasa', '24 €', true],
            ['Entrecot madurado', 'Entrecot madurado a la brasa con sal y romero.', '', 'De la brasa', '26 €', false],
            ['Lubina a la mediterránea', 'Lubina a la brasa con aceite, limón y hierbas.', '', 'De la brasa', '22 €', false],
            ['Paella de marisco', 'Arroz dorado con gambas, mejillones y calamar.', '', 'Arroces para compartir (mínimo 2 personas)', '21 €', false],
            ['Arroz meloso de pulpo y langostinos', 'Arroz cremoso con pulpo tierno y langostinos.', '/images/amaranta-mesa.jpg', 'Arroces para compartir (mínimo 2 personas)', '24 €', true],
            ['Fideuà de marisco', 'Fideos tostados con marisco y alioli.', '', 'Arroces para compartir (mínimo 2 personas)', '22 €', false],
            ['Gambas al ajillo', 'Gambas salteadas con ajo, guindilla y aceite.', '', 'Para empezar', '12 €', false],
            ['Mejillones al vino blanco', 'Mejillones abiertos al vapor de vino blanco.', '', 'Para empezar', '11 €', false],
            ['Tataki de atún rojo', 'Atún rojo marcado con sésamo y salsa de soja.', '/images/amaranta-ambiente.jpg', 'Para empezar', '18,50 €', true],
            ['Carpaccio de ternera', 'Láminas finas de ternera con aceite y parmesano.', '', 'Para empezar', '15 €', false],
            ['Patatas amarantas', 'Patatas asadas con pimentón y alioli suave.', '', 'Para empezar', '9,50 €', false],
            ['Tiramisú', 'Clásico tiramisú casero con mascarpone.', '', 'Postres y copas', '7 €', false],
            ['Tarta de la casa', 'Tarta casera del día, pregúntanos por ella.', '', 'Postres y copas', '7 €', false],
            ['Mojito', 'Ron, menta, lima y soda bien fresquitos.', '', 'Postres y copas', '10 €', false],
            ['Copa de vino tinto', 'Vino tinto de la casa, copa de 175 ml.', '', 'Postres y copas', '6 €', false],
            ['Caña', 'Caña bien tirada, la compañera de toda sobremesa.', '', 'Postres y copas', '3,50 €', false],
        ];

        foreach ($dishes as $index => [$title, $description, $image, $category, $price, $showOnHome]) {
            Dish::firstOrCreate(['title' => $title], [
                'description' => $description,
                'image_url' => $image,
                'category' => $category,
                'price_text' => $price,
                'show_on_home' => $showOnHome,
                'display_order' => $index + 1,
            ]);
        }
    }
}
