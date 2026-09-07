<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectorCache extends Model
{
    use HasFactory;

    protected $table = 'sectors_cache';

    protected $fillable = [
        'sector_code',
        'sector_name',
        'avg_der',
        'avg_npm',
        'raw_data',
        'last_synced_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'avg_der' => 'decimal:2',
            'avg_npm' => 'decimal:2',
            'raw_data' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Company, $this>
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'sector_id');
    }

    /**
     * @return HasMany<MacroAnalysisResult, $this>
     */
    public function analysisResults(): HasMany
    {
        return $this->hasMany(MacroAnalysisResult::class, 'sector_id');
    }
}
