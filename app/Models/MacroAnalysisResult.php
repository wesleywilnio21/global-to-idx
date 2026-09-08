<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MacroAnalysisResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'scenario_id',
        'sector_id',
        'impact_score',
        'resilience_status',
        'reasoning',
        'score_breakdown',
        'vulnerable_companies',
        'beneficiary_companies',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'impact_score' => 'integer',
            'score_breakdown' => 'array',
            'vulnerable_companies' => 'array',
            'beneficiary_companies' => 'array',
        ];
    }

    /**
     * Ensure score_breakdown always returns a structured array.
     *
     * @return array{
     *     revenue_impact: int,
     *     revenue_note: string,
     *     cost_impact: int,
     *     cost_note: string,
     *     balance_sheet_impact: int,
     *     balance_sheet_note: string
     * }
     */
    public function getScoreBreakdownAttribute($value): array
    {
        if (! empty($value)) {
            $decoded = is_array($value) ? $value : json_decode((string) $value, true);
            if (is_array($decoded) && isset($decoded['revenue_impact'])) {
                return $decoded;
            }
        }

        $score = (int) $this->impact_score;
        if ($score === 0) {
            return [
                'revenue_impact' => 0,
                'revenue_note' => 'Pendapatan sektor stabil, tidak terpapar guncangan makro.',
                'cost_impact' => 0,
                'cost_note' => 'Beban pokok penjualan & rantai pasok dalam kondisi normal.',
                'balance_sheet_impact' => 0,
                'balance_sheet_note' => 'Struktur permodalan (DER) dan marjin (NPM) seimbang.',
            ];
        }

        // Intelligently distribute total score across the 3 transmission pillars
        $rev = (int) round($score * 0.5);
        $cost = (int) round($score * 0.25);
        $bal = $score - $rev - $cost;

        $revNote = $rev > 0 ? 'Katalis positif volume & harga penjualan sektor.' : ($rev < 0 ? 'Tekanan penurunan permintaan & omset penjualan.' : 'Dampak netral terhadap pendapatan.');
        $costNote = $cost > 0 ? 'Beban biaya input stabil atau mendapat substitusi lokal.' : ($cost < 0 ? 'Kenaikan biaya bahan baku / energi / efek kurs impor.' : 'Beban operasional stabil.');
        $balNote = $bal > 0 ? 'Kas melimpah dengan rasio utang (DER) sehat sebagai penyangga.' : ($bal < 0 ? 'Beban bunga pinjaman atau leverage utang menekan marjin bersih.' : 'Struktur neraca seimbang.');

        return [
            'revenue_impact' => $rev,
            'revenue_note' => $revNote,
            'cost_impact' => $cost,
            'cost_note' => $costNote,
            'balance_sheet_impact' => $bal,
            'balance_sheet_note' => $balNote,
        ];
    }

    /**
     * @return BelongsTo<MacroScenario, $this>
     */
    public function scenario(): BelongsTo
    {
        return $this->belongsTo(MacroScenario::class, 'scenario_id');
    }

    /**
     * @return BelongsTo<SectorCache, $this>
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(SectorCache::class, 'sector_id');
    }
}
