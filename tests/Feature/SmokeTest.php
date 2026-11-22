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

        foreach (['/admin', '/admin/galeria', '/admin/testimonios', '/admin/contenido', '/admin/textos'] as $uri) {
            $this->get($uri)->assertOk();
        }
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
