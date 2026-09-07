<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'sector_id',
        'market_cap',
        'pe_ratio',
        'pbv_ratio',
        'der',
        'npm',
        'last_synced_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'market_cap' => 'integer',
            'pe_ratio' => 'decimal:2',
            'pbv_ratio' => 'decimal:2',
            'der' => 'decimal:2',
            'npm' => 'decimal:2',
            'last_synced_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<SectorCache, $this>
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(SectorCache::class, 'sector_id');
    }
}
