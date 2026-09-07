<?php

namespace Tests\Feature;

use App\Models\MacroScenario;
use Database\Seeders\InitialMacroDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MacroAnalysisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(InitialMacroDataSeeder::class);
    }

    public function test_dashboard_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('MacroSectors');
        $response->assertSee('IDX-ENERGY');
        $response->assertSee('Energi');
    }

    public function test_can_switch_to_preset_scenario(): void
    {
        $scenario = MacroScenario::where('is_preset', true)->first();
        $this->assertNotNull($scenario);

        $response = $this->get('/?scenario_id='.$scenario->id);

        $response->assertStatus(200);
        $response->assertSee($scenario->title);
    }

    public function test_can_analyze_custom_scenario(): void
    {
        $customText = 'Kenaikan tarif ekspor batubara dan pengenaan pajak karbon baru';

        $response = $this->post('/analyze', [
            'custom_scenario' => $customText,
        ]);

        $response->assertRedirect();

        $created = MacroScenario::where('description', $customText)->first();
        $this->assertNotNull($created);

        // Follow redirect
        $followResponse = $this->get(route('dashboard', ['scenario_id' => $created->id]));
        $followResponse->assertStatus(200);
        $this->assertDatabaseHas('macro_scenarios', [
            'description' => $customText,
        ]);
    }

    public function test_sectors_sync_endpoint(): void
    {
        $response = $this->post('/sectors/sync');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_upload_preview_page_renders_successfully(): void
    {
        $response = $this->get('/upload-preview');

        $response->assertStatus(200);
        $response->assertSee('Kirim Screenshot Preview');
    }
}
