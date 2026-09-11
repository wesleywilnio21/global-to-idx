<?php

namespace Tests\Feature;

use Tests\TestCase;

class HistoricalBacktestTest extends TestCase
{
    public function test_backtest_page_renders_successfully(): void
    {
        $response = $this->get('/backtest');

        $response->assertStatus(200);
        $response->assertSee('Kilas Balik Krisis');
        $response->assertSee('Covid-19');
    }

    public function test_can_switch_crisis_scenario(): void
    {
        $response = $this->get('/backtest?crisis=taper_tantrum_2013');
        $response->assertStatus(200);
        $response->assertSee('Taper Tantrum');

        $response2 = $this->get('/backtest?crisis=trade_war_2018');
        $response2->assertStatus(200);
        $response2->assertSee('Perang Dagang');
    }

    public function test_crisis_dataset_contains_all_11_sectors_and_accuracy(): void
    {
        $response = $this->get('/backtest?crisis=covid_crash_2020');
        $response->assertStatus(200);
        $response->assertViewHas(['crises', 'selectedCrisisKey', 'crisisData', 'sectorComparison', 'directionalAccuracy', 'topDefensive', 'topVulnerable']);

        $sectors = $response->viewData('sectorComparison');
        $this->assertCount(11, $sectors);

        foreach ($sectors as $sector) {
            $this->assertArrayHasKey('sector_name', $sector);
            $this->assertArrayHasKey('sector_code', $sector);
            $this->assertArrayHasKey('actual_return', $sector);
            $this->assertArrayHasKey('predicted_impact', $sector);
            $this->assertArrayHasKey('accuracy_status', $sector);
            $this->assertArrayHasKey('directional_match', $sector);
            $this->assertArrayHasKey('key_driver', $sector);
            $this->assertContains($sector['accuracy_status'], ['Bullseye (Presisi)', 'Konsisten', 'Divergen Minor']);
        }

        $accuracy = $response->viewData('directionalAccuracy');
        $this->assertGreaterThanOrEqual(80, $accuracy);

        $crisisData = $response->viewData('crisisData');
        $this->assertArrayHasKey('usd_fx', $crisisData);
        $this->assertArrayHasKey('bi_rate', $crisisData);
        $this->assertArrayHasKey('inflation_gdp', $crisisData);
        $this->assertArrayHasKey('ihsg_drawdown', $crisisData);
        $this->assertArrayHasKey('ai_post_mortem', $crisisData);

        $topDefensive = $response->viewData('topDefensive');
        $this->assertNotEmpty($topDefensive);
        $topVulnerable = $response->viewData('topVulnerable');
        $this->assertNotEmpty($topVulnerable);
    }

    public function test_default_scenario_is_covid_crash_2020(): void
    {
        $response = $this->get('/backtest');
        $response->assertStatus(200);
        $this->assertEquals('covid_crash_2020', $response->viewData('selectedCrisisKey'));
    }

    public function test_invalid_crisis_query_falls_back_to_default(): void
    {
        $response = $this->get('/backtest?crisis=unknown_crisis_xyz');
        $response->assertStatus(200);
        $this->assertEquals('covid_crash_2020', $response->viewData('selectedCrisisKey'));
    }

    public function test_all_three_crises_have_complete_datasets(): void
    {
        $crises = ['taper_tantrum_2013', 'trade_war_2018', 'covid_crash_2020'];

        foreach ($crises as $key) {
            $response = $this->get('/backtest?crisis='.$key);
            $response->assertStatus(200);
            $this->assertEquals($key, $response->viewData('selectedCrisisKey'));

            $sectors = $response->viewData('sectorComparison');
            $this->assertCount(11, $sectors);

            $accuracy = $response->viewData('directionalAccuracy');
            $this->assertGreaterThanOrEqual(80, $accuracy);
        }
    }
}
