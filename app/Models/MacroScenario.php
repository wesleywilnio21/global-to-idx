<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MacroScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'is_preset',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_preset' => 'boolean',
        ];
    }

    /**
     * @return HasMany<MacroAnalysisResult, $this>
     */
    public function analysisResults(): HasMany
    {
        return $this->hasMany(MacroAnalysisResult::class, 'scenario_id');
    }
}
