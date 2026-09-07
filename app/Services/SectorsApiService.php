<?php

namespace App\Services;

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
        $this->baseUrl = rtrim((string) config('sectors.base_url', 'https://api.sectors.app/v1'), '/');
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
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get("{$this->baseUrl}/sectors/");

            if ($response->successful() && is_array($response->json())) {
                return $response->json();
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
    public function syncAll(): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'synced_sectors' => SectorCache::count(),
                'message' => 'API Key Sectors belum dikonfigurasi di .env. Menggunakan data cache lokal.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'X-API-KEY' => $this->apiKey,
            ])->timeout(15)->get("{$this->baseUrl}/sectors/");

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'synced_sectors' => 0,
                    'message' => 'Gagal menghubungi Sectors API (Status: '.$response->status().').',
                ];
            }

            $sectorsData = $response->json();
            $count = 0;
            $now = Carbon::now();

            if (is_array($sectorsData)) {
                foreach ($sectorsData as $item) {
                    $code = $item['sector'] ?? $item['sector_code'] ?? null;
                    $name = $item['name'] ?? $item['sector_name'] ?? $code;

                    if ($code) {
                        SectorCache::updateOrCreate(
                            ['sector_code' => $code],
                            [
                                'sector_name' => $name,
                                'avg_der' => $item['avg_der'] ?? null,
                                'avg_npm' => $item['avg_npm'] ?? null,
                                'raw_data' => $item,
                                'last_synced_at' => $now,
                            ]
                        );
                        $count++;
                    }
                }
            }

            return [
                'success' => true,
                'synced_sectors' => $count,
                'message' => "Berhasil menyinkronkan {$count} sektor dari Sectors API.",
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
