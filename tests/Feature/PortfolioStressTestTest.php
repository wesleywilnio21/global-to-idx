<?php

namespace Tests\Feature;

use Database\Seeders\InitialMacroDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioStressTestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(InitialMacroDataSeeder::class);
    }

    public function test_portfolio_page_renders_successfully(): void
    {
        $response = $this->get(route('portfolio.index'));

        $response->assertStatus(200);
        $response->assertSee('Stress-Test Portofolio');
        $response->assertSee('Hasil Evaluasi Ketahanan Portofolio');
        $response->assertSee('Rata-rata Terbobot DER');
    }

    public function test_can_switch_portfolio_presets(): void
    {
        $response = $this->get(route('portfolio.index', ['preset' => 'commodity']));

        $response->assertStatus(200);
        $response->assertSee('Eksportir Energi & Komoditas USD');
        $response->assertSee('ADRO');
        $response->assertSee('MEDC');
    }

    public function test_can_submit_custom_portfolio_holdings(): void
    {
        $response = $this->post(route('portfolio.index'), [
            'preset' => 'custom',
            'holdings' => [
                ['symbol' => 'BBCA', 'weight' => 50],
                ['symbol' => 'ADRO', 'weight' => 50],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertSee('BBCA');
        $response->assertSee('ADRO');
        $response->assertSee('50%');
    }
}
