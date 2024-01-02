<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrisonerConfirm extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        self::deleting(function ($PrisonerConfirm) {
            $PrisonerConfirm->ArrestConfirm()->each(function ($ArrestConfirm) {
                $ArrestConfirm->delete();
            });
            $PrisonerConfirm->OldArrestConfirm()->each(function ($OldArrestConfirm) {
                $OldArrestConfirm->delete();
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

    public function ArrestConfirm(): HasOne
    {
        return $this->hasOne(ArrestConfirm::class);
    }

    public function OldArrestConfirm(): HasMany
    {
        return $this->hasMany(OldArrestConfirm::class);
    }
}
