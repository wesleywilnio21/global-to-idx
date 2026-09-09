<?php

namespace Tests\Feature;

use App\Models\MacroScenario;
use Database\Seeders\InitialMacroDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MacroReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(InitialMacroDataSeeder::class);
    }

    public function test_tear_sheet_page_renders_successfully(): void
    {
        $response = $this->get(route('report.tear-sheet'));

        $response->assertStatus(200);
        $response->assertSee('Institutional Macro Tear-Sheet');
        $response->assertSee('INSTITUTIONAL MACRO RESEARCH BRIEF');
        $response->assertSee('Matriks Sensitivitas Transmisi 11 Sektor IHSG');
        $response->assertSee('DOC ID:');
        $response->assertSee('Rekomendasi Alokasi Taktis Sektor');
        $response->assertSee('Pernyataan Kepatuhan');
    }

    public function test_tear_sheet_with_specific_scenario(): void
    {
        $scenario = MacroScenario::where('is_preset', true)->first();

        $response = $this->get(route('report.tear-sheet', ['scenario_id' => $scenario->id]));

        $response->assertStatus(200);
        $response->assertSee($scenario->title);
        $response->assertSee('Market Stance');
    }

    public function test_dashboard_has_link_to_tear_sheet(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Export Tear-Sheet');
        $response->assertSee(route('report.tear-sheet'));
    }

    public function test_sidebar_has_link_to_tear_sheet(): void
    {
        $response = $this->get(route('report.tear-sheet'));

        $response->assertStatus(200);
        $response->assertSee('Cetak / Simpan PDF');
    }
}
