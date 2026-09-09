<?php

namespace Tests\Feature;

use Database\Seeders\InitialMacroDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeadToHeadDuelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(InitialMacroDataSeeder::class);
    }

    public function test_duel_page_renders_successfully(): void
    {
        $response = $this->get(route('duel.index'));

        $response->assertStatus(200);
        $response->assertSee('Head-to-Head Duel');
        $response->assertSee('Arena Komparasi Transmisi Makro');
        $response->assertSee('Showdown Kuantitatif');
        $response->assertSee('BBCA');
        $response->assertSee('BBRI');
    }

    public function test_can_switch_fighters_in_stock_mode(): void
    {
        $response = $this->post(route('duel.index'), [
            'mode' => 'stock',
            'fighter_a' => 'ADRO',
            'fighter_b' => 'MEDC',
        ]);

        $response->assertStatus(200);
        $response->assertSee('ADRO');
        $response->assertSee('MEDC');
        $response->assertSee('Pilar Pendapatan (Revenue)');
    }

    public function test_can_run_sector_duel_mode(): void
    {
        $response = $this->post(route('duel.index'), [
            'mode' => 'sector',
            'fighter_a' => 'idx-energy',
            'fighter_b' => 'idx-transportation',
        ]);

        $response->assertStatus(200);
        $response->assertSee('idx-energy');
        $response->assertSee('idx-transportation');
        $response->assertSee('Sektor Energi');
        $response->assertSee('Sektor Transportasi');
    }

    public function test_can_trigger_preset_duel(): void
    {
        $response = $this->get(route('duel.index', ['preset' => 'banking']));

        $response->assertStatus(200);
        $response->assertSee('BBCA');
        $response->assertSee('BBRI');
        $response->assertSee('Analisis Strategis');
        $response->assertSee('Tindakan Portofolio');
    }
}
