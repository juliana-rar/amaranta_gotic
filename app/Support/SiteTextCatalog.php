<?php

namespace App\Support;

final class SiteTextCatalog
{
    /** @return array<int, array{key: string, group: string, label: string, value: string, multiline: bool}> */
    public static function definitions(): array
    {
        $rows = [
            ['nav.home', 'Navegación', 'Inicio', 'Inicio'],
            ['nav.menu', 'Navegación', 'Menú', 'Menú'],
            ['nav.groups', 'Navegación', 'Grupos', 'Grupos'],
            ['nav.location', 'Navegación', 'Ubicación', 'Ubicación'],
            ['nav.contact', 'Navegación', 'Contacto', 'Contacto'],
            ['nav.reserve', 'Navegación', 'Reservar mesa', 'Reservar mesa'],

            ['home.hero.cta', 'Inicio', 'Botón reservar', 'Reservar mesa'],
            ['home.hero.secondary', 'Inicio', 'Botón menú', 'Ver el menú'],
            ['home.hero.trust1', 'Inicio', 'Dato destacado 1', 'Barri Gòtic, Barcelona'],
            ['home.hero.trust2', 'Inicio', 'Dato destacado 2', 'Cocina a la brasa'],
            ['home.hero.trust3', 'Inicio', 'Dato destacado 3', 'Mesas para grupos'],
            ['home.menu.title', 'Inicio', 'Título de la carta', 'Nuestra carta'],
            ['home.menu.cta', 'Inicio', 'Botón de la carta', 'Ver la carta completa'],
            ['home.origin.heading1', 'Inicio', 'Título historia 1', 'El barrio'],
            ['home.origin.heading2', 'Inicio', 'Título historia 2', 'La brasa'],
            ['home.origin.heading3', 'Inicio', 'Título historia 3', 'Nuestra firma'],
            ['home.ribbon', 'Inicio', 'Cinta de reservas', '¡Reserva tu mesa en Amaranta Gótico! Llama al {phone}'],
            ['home.break.title', 'Inicio', 'Título foto grande', 'Arroces y brasa para compartir, a un paso de la Catedral'],
            ['home.break.cta', 'Inicio', 'Botón foto grande', 'Ver grupos'],
            ['home.dishes.eyebrow', 'Inicio', 'Etiqueta de platos', 'De la brasa'],
            ['home.dishes.title', 'Inicio', 'Título de platos', 'Platos para compartir en el Gótico'],
            ['home.gallery.eyebrow', 'Inicio', 'Etiqueta de galería', 'Galería'],
            ['home.gallery.title', 'Inicio', 'Título de galería', 'Así es Amaranta Gótico'],
            ['home.testimonials.eyebrow', 'Inicio', 'Etiqueta de opiniones', 'Opiniones'],
            ['home.testimonials.title', 'Inicio', 'Título de opiniones', 'Lo que dicen nuestros comensales'],
            ['home.cta.title', 'Inicio', 'Título cierre', '¿Vienes en grupo o celebras algo especial?'],
            ['home.cta.text', 'Inicio', 'Texto cierre', 'Mesas juntas, arroces para compartir y la brasa encendida: cuéntanos qué celebras y lo preparamos contigo.', true],
            ['home.cta.button', 'Inicio', 'Botón cierre', 'Ver grupos y eventos'],

            ['menu.hero.title', 'Carta', 'Título', 'La carta'],
            ['menu.hero.subtitle', 'Carta', 'Subtítulo', 'Cocina mediterránea a la brasa, arroces melosos y fideuás para compartir. Producto, técnica y alma en cada mesa del Gótico.', true],
            ['menu.groups.title', 'Carta', 'Título menús de grupo', 'Menús de grupo'],
            ['menu.groups.line1', 'Carta', 'Menú de grupo 1', 'Menú Brasa — desde 24 € por persona'],
            ['menu.groups.line2', 'Carta', 'Menú de grupo 2', 'Menú Banquete — consulta precio según grupo'],
            ['menu.groups.line3', 'Carta', 'Menú de grupo 3', 'Privatización del local — hasta 50 personas'],
            ['menu.groups.cta', 'Carta', 'Botón grupos', 'Ver grupos y eventos'],
            ['menu.empty', 'Carta', 'Sin platos', 'Todavía no hay platos disponibles.'],

            ['grupos.hero.eyebrow', 'Grupos y eventos', 'Etiqueta', 'Grupos y eventos'],
            ['grupos.hero.title', 'Grupos y eventos', 'Título', 'La mesa larga'],
            ['grupos.hero.text', 'Grupos y eventos', 'Texto', 'Cuando se sienta mucha gente alrededor, la brasa hace el resto. Reúnase tu equipo, tu familia o tus amigos: juntamos las mesas, encendemos el fuego y servimos arroces para compartir en plena callejuela del Gótico.', true],
            ['grupos.s1.title', 'Grupos y eventos', 'Título bloque 1', 'Menús de grupo'],
            ['grupos.s1.col1.title', 'Grupos y eventos', 'Título columna 1', 'La mesa, lista'],
            ['grupos.s1.col1.line1', 'Grupos y eventos', 'Texto 1.1', 'Menú Brasa — desde 24 € por persona'],
            ['grupos.s1.col1.line2', 'Grupos y eventos', 'Texto 1.2', 'Mínimo 10 personas'],
            ['grupos.s1.col1.line3', 'Grupos y eventos', 'Texto 1.3', 'Mesas juntas bajo la luz baja del local'],
            ['grupos.s1.col2.title', 'Grupos y eventos', 'Título columna 2', 'Para compartir'],
            ['grupos.s1.col2.line1', 'Grupos y eventos', 'Texto 2.1', 'Arroces melosos y fideuás en el centro de la mesa'],
            ['grupos.s1.col2.line2', 'Grupos y eventos', 'Texto 2.2', 'Carnes y verduras a la brasa'],
            ['grupos.s1.col2.line3', 'Grupos y eventos', 'Texto 2.3', 'Tapas elaboradas y coctelería'],
            ['grupos.s2.title', 'Grupos y eventos', 'Título bloque 2', 'Celebraciones, afterworks y privatizaciones'],
            ['grupos.s2.col1.title', 'Grupos y eventos', 'Título columna 3', 'Tu ocasión'],
            ['grupos.s2.col1.line1', 'Grupos y eventos', 'Texto 3.1', 'Cenas de empresa, cumpleaños y afterworks'],
            ['grupos.s2.col1.line2', 'Grupos y eventos', 'Texto 3.2', 'Eventos y privatizaciones del local'],
            ['grupos.s2.col1.line3', 'Grupos y eventos', 'Texto 3.3', 'Hasta 50 personas · 46 sentadas'],
            ['grupos.s2.col2.title', 'Grupos y eventos', 'Título columna 4', 'Cómo lo hacemos'],
            ['grupos.s2.col2.line1', 'Grupos y eventos', 'Texto 4.1', 'Hablamos contigo y elegimos el menú a medida'],
            ['grupos.s2.col2.line2', 'Grupos y eventos', 'Texto 4.2', 'Reservamos la zona o el local completo'],
            ['grupos.s2.col2.line3', 'Grupos y eventos', 'Texto 4.3', 'Nos ocupamos del fuego; tú solo llegas'],
            ['grupos.s3.title', 'Grupos y eventos', 'Título contacto', 'Hablemos de tu grupo'],
            ['grupos.s3.phone.label', 'Grupos y eventos', 'Etiqueta teléfono', 'Llámanos'],
            ['grupos.s3.email.label', 'Grupos y eventos', 'Etiqueta email', 'Escríbenos'],
            ['grupos.s3.cta1', 'Grupos y eventos', 'Botón contacto', 'Contactar'],
            ['grupos.s3.cta2', 'Grupos y eventos', 'Botón reservar', 'Reservar mesa'],

            ['ubicacion.hero.title', 'Ubicación', 'Título', 'Dónde estamos'],
            ['ubicacion.hero.subtitle', 'Ubicación', 'Subtítulo', 'Un callejón del Gótico, a un paso de la Catedral.'],
            ['ubicacion.address.title', 'Ubicación', 'Título dirección', 'Dirección'],
            ['ubicacion.hours.title', 'Ubicación', 'Título horario', 'Horario'],
            ['ubicacion.transport', 'Ubicación', 'Cómo llegar', 'Llegas con el metro L4 hasta Jaume I o con el L3 hasta Liceu; desde la Plaça Reial, un paseo corto por callejones de piedra te deja en nuestra puerta.', true],

            ['contacto.hero.title', 'Contacto', 'Título', 'Hablemos de tu mesa'],
            ['contacto.hero.text', 'Contacto', 'Texto', 'Grupos, cenas de empresa, cumpleaños o privatizaciones: cuéntanos qué tienes en mente y te respondemos con la mesa lista.', true],
            ['contacto.hero.groups', 'Contacto', 'Texto grupos', 'Para grupos de 10 personas o más, llámanos al {phone} y lo organizamos contigo.'],
            ['contacto.phone.label', 'Contacto', 'Etiqueta teléfono', 'Teléfono'],
            ['contacto.email.label', 'Contacto', 'Etiqueta email', 'Email'],
            ['contacto.instagram.label', 'Contacto', 'Etiqueta Instagram', 'Instagram'],

            ['reservar.title', 'Reservas', 'Título', 'Reserva tu mesa'],
            ['reservar.intro', 'Reservas', 'Texto', 'Comida de 13:00 a 16:00 y cena de 20:00 a 23:00 en Amaranta Gótico. Si vienes en grupo de 10 personas o más, contáctanos y preparamos la mesa larga contigo.', true],
            ['reservar.success', 'Reservas', 'Mensaje final', '¡Gracias, {name}! Hemos recibido tu solicitud en Amaranta Gótico para el {date} a las {time}. Te confirmaremos por teléfono en breve.', true],
            ['reservar.step1', 'Reservas', 'Paso 1', 'Datos'],
            ['reservar.step2', 'Reservas', 'Paso 2', 'Fecha'],
            ['reservar.step3', 'Reservas', 'Paso 3', 'Hora'],
            ['reservar.step4', 'Reservas', 'Paso 4', 'Resumen'],

            ['footer.description', 'Pie de página', 'Descripción', 'Brasería mediterránea en el Barrio Gótico de Barcelona. Carnes a la brasa, arroces para compartir y copas entre piedras y brasas, a un paso de la Catedral.', true],
            ['footer.hours.title', 'Pie de página', 'Título horario', 'Horario'],
            ['footer.location.title', 'Pie de página', 'Título ubicación', 'Ubicación'],
            ['footer.copyright', 'Pie de página', 'Copyright', 'Amaranta Gótico. Todos los derechos reservados.'],
            ['footer.cta', 'Pie de página', 'Botón reservar', 'Reservar mesa'],
            ['error.title', 'Pie de página', 'Mensaje de error', 'Ha ocurrido un error inesperado.'],
            ['error.reload', 'Pie de página', 'Botón recargar', 'Recargar'],

            ['admin.sidebar.brand', 'Panel', 'Nombre del panel', 'Panel de Amaranta Gótico'],
            ['admin.nav.carta', 'Panel', 'Enlace carta', '🔥 Carta'],
            ['admin.nav.galeria', 'Panel', 'Enlace fotos', '📷 Fotos'],
            ['admin.nav.opiniones', 'Panel', 'Enlace opiniones', '💬 Opiniones'],
            ['admin.nav.horarios', 'Panel', 'Enlace horarios', '🕒 Horarios'],
            ['admin.nav.contenido', 'Panel', 'Enlace contenido', '📝 Contenido'],
            ['admin.nav.textos', 'Panel', 'Enlace textos', '✍️ Textos'],
        ];

        return array_map(
            fn (array $row) => [
                'key' => $row[0],
                'group' => $row[1],
                'label' => $row[2],
                'value' => $row[3],
                'multiline' => (bool) ($row[4] ?? false),
            ],
            $rows
        );
    }

    /** @return array<int, string> */
    public static function groups(): array
    {
        return array_values(array_unique(array_column(self::definitions(), 'group')));
    }
}
