<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render(): void
    {
        foreach (['/', '/menu', '/reservar', '/contacto', '/ubicacion', '/grupos', '/login', '/register'] as $uri) {
            $this->get($uri)->assertOk();
        }

        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_admin_login_and_pages_render(): void
    {
        $this->seed();

        $this->post('/login', ['username' => 'admin', 'password' => 'admin1234'])->assertRedirect('/admin');

        foreach (['/admin', '/admin/galeria', '/admin/testimonios', '/admin/horarios', '/admin/contenido', '/admin/textos'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_admin_can_save_hours(): void
    {
        $this->seed();

        $this->post('/login', ['username' => 'admin', 'password' => 'admin1234'])->assertRedirect('/admin');

        $this->post('/admin/horarios', [
            'location_hours' => 'Lun–Dom · 12:00–16:00 y 19:00–23:00',
            'hours' => [
                0 => ['is_open' => 1, 'shift1_start' => '12:00', 'shift1_end' => '16:00', 'has_shift2' => 1, 'shift2_start' => '19:00', 'shift2_end' => '23:00'],
            ],
        ])->assertRedirect('/admin/horarios');

        $this->assertDatabaseHas('business_hours', [
            'day_order' => 0,
            'shift1_start' => '12:00',
            'shift2_end' => '23:00',
        ]);

        $this->assertDatabaseHas('site_contents', [
            'id' => 1,
            'location_hours' => 'Lun–Dom · 12:00–16:00 y 19:00–23:00',
        ]);
    }

    public function test_reservation_wizard_saves_locally(): void
    {
        $date = now()->addDay()->toDateString();

        $this->post('/reservar', [
            'step' => 1, 'go' => 2, 'customer_name' => 'Ana', 'party_size' => 2,
        ])->assertRedirect('/reservar');

        $this->post('/reservar', [
            'step' => 2, 'go' => 3, 'date' => $date,
        ])->assertRedirect('/reservar');

        $this->post('/reservar', [
            'step' => 3, 'go' => 4, 'period' => 'lunch', 'time' => '13:30',
        ])->assertRedirect('/reservar');

        $this->post('/reservar', [
            'step' => 4, 'submit' => 1,
        ])->assertOk()->assertSee('Solicitud recibida');

        $this->assertDatabaseHas('reservations', [
            'customer_name' => 'Ana',
            'date' => $date.' 00:00:00',
            'time' => '13:30',
            'party_size' => 2,
            'status' => 'pendiente',
        ]);
    }
}
