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
            'vulnerable_companies' => 'array',
            'beneficiary_companies' => 'array',
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
