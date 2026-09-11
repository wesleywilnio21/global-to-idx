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
}
