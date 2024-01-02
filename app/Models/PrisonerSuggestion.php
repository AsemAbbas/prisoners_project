<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrisonerSuggestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        self::deleting(function ($PrisonerSuggestion) {
            $PrisonerSuggestion->ArrestSuggestion()->each(function ($ArrestSuggestion) {
                $ArrestSuggestion->delete();
            });
            $PrisonerSuggestion->OldArrestSuggestion()->each(function ($OldArrestSuggestion) {
                $OldArrestSuggestion->delete();
            });
        });
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->second_name . ' ' . $this->third_name . ' ' . $this->last_name;
    }

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function Town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }

    public function Relationship(): BelongsTo
    {
        return $this->belongsTo(Relationship::class);
    }

    public function ArrestSuggestion(): HasOne
    {
        return $this->hasOne(ArrestSuggestion::class);
    }

    public function OldArrestSuggestion(): HasMany
    {
        return $this->hasMany(OldArrestSuggestion::class);
    }
}
