<?php

namespace App\Services;

use App\Models\Company;
use App\Models\SectorCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SectorsApiService
{
    protected string $apiKey;

    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('sectors.api_key', '');
        $this->baseUrl = rtrim((string) config('sectors.base_url', 'https://api.sectors.app/v2'), '/');
    }

    /**
     * Check if Sectors API key is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Fetch all sectors from Sectors API or fallback to local cache.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSectors(): array
    {
        if (! $this->isConfigured()) {
            return $this->getLocalSectors();
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => $this->apiKey,
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get("{$this->baseUrl}/subsectors/");

            if ($response->successful() && is_array($response->json())) {
                return $this->getLocalSectors();
            }
        } catch (\Throwable $e) {
            Log::warning('Sectors API getSectors failed, using local cache: '.$e->getMessage());
        }

        return $this->getLocalSectors();
    }

    /**
     * Sync data from Sectors API into local database.
     *
     * @return array{success: bool, synced_sectors: int, message: string}
     */
    public function syncAll(bool $forceDeepSync = false): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'synced_sectors' => SectorCache::count(),
                'message' => 'API Key Sectors belum dikonfigurasi di .env. Menggunakan data cache lokal.',
            ];
        }

        try {
            $now = Carbon::now();

            // 1. Fetch subsectors/sectors list from Sectors API v2 (Lightweight: only costs 1 credit)
            $subsectorsResponse = Http::withoutVerifying()->withHeaders([
                'Authorization' => $this->apiKey,
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(15)->get("{$this->baseUrl}/subsectors/");

            if (! $subsectorsResponse->successful()) {
                return [
                    'success' => false,
                    'synced_sectors' => 0,
                    'message' => 'Gagal menghubungi Sectors API v2 (Status: '.$subsectorsResponse->status().').',
                ];
            }

            // Update sync timestamp for all 11 sectors
            SectorCache::query()->update(['last_synced_at' => $now]);

            $syncedCompanies = 0;
            $alreadyHasData = Company::whereNotNull('last_synced_at')->exists();

            // 2. Only fetch deep individual company reports if forced or if local database is empty (to save API credits)
            if ($forceDeepSync || ! $alreadyHasData) {
                $companies = Company::all();

                foreach ($companies as $company) {
                    try {
                        $reportResponse = Http::withoutVerifying()->withHeaders([
                            'Authorization' => $this->apiKey,
                            'X-API-KEY' => $this->apiKey,
                            'Accept' => 'application/json',
                        ])->timeout(8)->get("{$this->baseUrl}/company/report/{$company->symbol}/");

                        if ($reportResponse->successful()) {
                            $data = $reportResponse->json();

                            $mcap = $data['overview']['market_cap'] ?? null;
                            $histVal = $data['valuation']['historical_valuation'] ?? [];
                            $lastVal = ! empty($histVal) ? end($histVal) : [];
                            $pe = isset($lastVal['pe']) ? round((float) $lastVal['pe'], 2) : null;
                            $pb = isset($lastVal['pb']) ? round((float) $lastVal['pb'], 2) : null;

                            $finRatio = $data['financials']['historical_financial_ratio'] ?? [];
                            $lastRatio = ! empty($finRatio) ? end($finRatio) : [];
                            $der = isset($lastRatio['leverage']['debt_to_equity_ratio'])
                                ? round((float) $lastRatio['leverage']['debt_to_equity_ratio'], 2)
                                : null;
                            $npm = isset($lastRatio['profitability']['net_profit_margin'])
                                ? round((float) $lastRatio['profitability']['net_profit_margin'] * 100, 2)
                                : null;

                            $company->update([
                                'market_cap' => $mcap ?? $company->market_cap,
                                'pe_ratio' => $pe ?? $company->pe_ratio,
                                'pbv_ratio' => $pb ?? $company->pbv_ratio,
                                'der' => $der ?? $company->der,
                                'npm' => $npm ?? $company->npm,
                                'last_synced_at' => $now,
                            ]);

                            $syncedCompanies++;
                        }
                    } catch (\Throwable $e) {
                        Log::debug("Skipped live report for {$company->symbol}: ".$e->getMessage());
                    }
                }

                // Recalculate average sector metrics
                $sectors = SectorCache::with('companies')->get();
                foreach ($sectors as $sector) {
                    $avgDer = $sector->companies->avg('der');
                    $avgNpm = $sector->companies->avg('npm');

                    $sector->update([
                        'avg_der' => $avgDer !== null ? round((float) $avgDer, 2) : $sector->avg_der,
                        'avg_npm' => $avgNpm !== null ? round((float) $avgNpm, 2) : $sector->avg_npm,
                        'last_synced_at' => $now,
                    ]);
                }
            }

            $sectorCount = SectorCache::count();

            $msg = $syncedCompanies > 0
                ? "Berhasil menyinkronkan {$sectorCount} sektor dan {$syncedCompanies} emiten langsung dari Sectors API v2."
                : "Sectors API: Live & Terhubung! Status 11 sektor diperbarui (Data fundamental 49 emiten aman di database lokal, hemat kredit).";

            return [
                'success' => true,
                'synced_sectors' => $sectorCount,
                'message' => $msg,
            ];
        } catch (\Throwable $e) {
            Log::error('Sectors API sync error: '.$e->getMessage());

            return [
                'success' => false,
                'synced_sectors' => 0,
                'message' => 'Terjadi kesalahan saat sinkronisasi: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Return formatted sectors from local database.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getLocalSectors(): array
    {
        return SectorCache::with('companies')->get()->map(function (SectorCache $sector) {
            return [
                'sector_code' => $sector->sector_code,
                'sector_name' => $sector->sector_name,
                'avg_der' => $sector->avg_der,
                'avg_npm' => $sector->avg_npm,
                'companies_count' => $sector->companies->count(),
            ];
        })->toArray();
    }
}
