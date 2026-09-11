<?php

namespace Tests\Feature;

use App\Services\MacroCopilotService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MacroCopilotTest extends TestCase
{
    public function test_service_returns_structured_response_with_deterministic_fallback_when_api_key_empty(): void
    {
        Config::set('sectors.ai.gemini_api_key', '');

        $service = app(MacroCopilotService::class);
        $result = $service->ask('Bagaimana dampak penguatan USD terhadap IHSG?');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('answer', $result);
        $this->assertArrayHasKey('engine', $result);
        $this->assertArrayHasKey('suggested_followups', $result);

        $this->assertNotEmpty($result['answer']);
        $this->assertSame('MacroSectors Deterministic Engine (Zero-Latency Fallback)', $result['engine']);
        $this->assertIsArray($result['suggested_followups']);
        $this->assertGreaterThanOrEqual(2, count($result['suggested_followups']));
        foreach ($result['suggested_followups'] as $followup) {
            $this->assertIsString($followup);
            $this->assertNotEmpty($followup);
        }
    }

    public function test_service_handles_different_page_contexts(): void
    {
        Config::set('sectors.ai.gemini_api_key', '');
        $service = app(MacroCopilotService::class);

        $pages = ['/scanner', '/portfolio', '/backtest', '/duel', '/dashboard', null];

        foreach ($pages as $page) {
            $result = $service->ask('Sektor apa yang paling tahan krisis?', $page);

            $this->assertIsArray($result);
            $this->assertNotEmpty($result['answer']);
            $this->assertSame('MacroSectors Deterministic Engine (Zero-Latency Fallback)', $result['engine']);
            $this->assertGreaterThanOrEqual(2, count($result['suggested_followups']));
        }
    }

    public function test_service_handles_key_macro_topics_deterministically(): void
    {
        Config::set('sectors.ai.gemini_api_key', '');
        $service = app(MacroCopilotService::class);

        // 1. Currency / USD / Kurs
        $fx = $service->ask('Bagaimana jika USD tembus Rp16.800?');
        $this->assertStringContainsStringIgnoringCase('USD', $fx['answer']);
        $this->assertNotEmpty($fx['suggested_followups']);

        // 2. Interest Rate / BI-Rate / Bunga
        $rates = $service->ask('Apakah kenaikan BI-Rate memukul sektor properti?');
        $this->assertNotEmpty($rates['answer']);
        $this->assertNotEmpty($rates['suggested_followups']);

        // 3. Banking Comparison BBCA vs BBRI
        $bank = $service->ask('Kenapa BBCA lebih defensif dari BBRI saat suku bunga naik?');
        $this->assertStringContainsStringIgnoringCase('BBCA', $bank['answer']);
        $this->assertNotEmpty($bank['suggested_followups']);

        // 4. Vulnerability / Solvency / Red-Line
        $solvency = $service->ask('Emiten mana yang terancam Red-Line hari ini?', '/scanner');
        $this->assertNotEmpty($solvency['answer']);
        $this->assertNotEmpty($solvency['suggested_followups']);

        // 5. Commodities / Energy
        $energy = $service->ask('Bagaimana prospek sektor batubara dan energi jika harga minyak melonjak?');
        $this->assertNotEmpty($energy['answer']);
        $this->assertNotEmpty($energy['suggested_followups']);

        // 6. Sector Rotation
        $rotation = $service->ask('Strategi rotasi sektor terbaik semester ini?');
        $this->assertNotEmpty($rotation['answer']);
        $this->assertNotEmpty($rotation['suggested_followups']);
    }

    public function test_service_calls_gemini_api_when_key_present(): void
    {
        Config::set('sectors.ai.gemini_api_key', 'mock-gemini-key');
        Config::set('sectors.ai.gemini_model', 'gemini-3.1-flash-lite');

        $fakeAnswer = "Pelemahan nilai tukar Rupiah terhadap USD memberikan tekanan signifikan pada emiten dengan beban utang valas tinggi.\n\nSaran Follow-up:\n1. Bagaimana posisi utang valas KLBF vs KAEF?\n2. Sektor apa yang paling kebal terhadap depresiasi rupiah?";

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => $fakeAnswer],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = app(MacroCopilotService::class);
        $result = $service->ask('Bagaimana dampak kurs USD ke emiten farmasi?', '/scanner');

        $this->assertIsArray($result);
        $this->assertStringContainsString('Google Gemini (gemini-3.1-flash-lite)', $result['engine']);
        $this->assertStringContainsString('Pelemahan nilai tukar Rupiah', $result['answer']);
        $this->assertGreaterThanOrEqual(1, count($result['suggested_followups']));
    }

    public function test_service_falls_back_to_deterministic_when_gemini_api_fails(): void
    {
        Config::set('sectors.ai.gemini_api_key', 'mock-gemini-key');

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response('Internal Server Error', 500),
        ]);

        $service = app(MacroCopilotService::class);
        $result = $service->ask('Bagaimana dampak suku bunga tinggi?');

        $this->assertIsArray($result);
        $this->assertSame('MacroSectors Deterministic Engine (Zero-Latency Fallback)', $result['engine']);
        $this->assertNotEmpty($result['answer']);
        $this->assertGreaterThanOrEqual(2, count($result['suggested_followups']));
    }

    public function test_copilot_ask_endpoint_validates_required_message(): void
    {
        $response = $this->postJson('/api/copilot/ask', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    public function test_copilot_ask_endpoint_returns_successful_json_response(): void
    {
        Config::set('sectors.ai.gemini_api_key', '');

        $response = $this->postJson('/api/copilot/ask', [
            'message' => 'Bagaimana dampak kurs USD ke BBCA?',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNotEmpty($response->json('data.answer'));
        $this->assertNotEmpty($response->json('data.engine'));
        $this->assertIsArray($response->json('data.suggested_followups'));
    }

    public function test_copilot_ask_endpoint_handles_page_context(): void
    {
        Config::set('sectors.ai.gemini_api_key', '');

        $response = $this->postJson('/api/copilot/ask', [
            'message' => 'Apa emiten rentan?',
            'page' => '/scanner',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertStringContainsString('Scanner', $response->json('data.answer'));
    }
}
