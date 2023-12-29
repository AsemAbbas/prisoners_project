<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prisoner extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        self::deleting(function ($Prisoner) {
            $Prisoner->Arrest()->each(function ($Arrest) {
                $Arrest->delete();
            });
            $Prisoner->OldArrest()->each(function ($OldArrest) {
                $OldArrest->delete();
            });
            $Prisoner->RelativesPrisoner()->each(function ($RelativesPrisoner) {
                $RelativesPrisoner->delete();
            });
            PrisonersPrisonerTypes::query()
                ->where('prisoner_id', $Prisoner->id)
                ->delete();
        });
    }

    public function Arrest(): HasOne
    {
        return $this->hasOne(Arrest::class);
    }

    public function OldArrest(): HasMany
    {
        return $this->hasMany(OldArrest::class);
    }

    public function RelativesPrisoner(): HasMany
    {
        return $this->hasMany(RelativesPrisoner::class);
    }

    public function PrisonerType(): BelongsToMany
    {
        return $this->belongsToMany(PrisonerType::class, 'prisoners_prisoner_types', 'prisoner_id', 'prisoner_type_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->second_name . ' ' . $this->third_name . ' ' . $this->last_name;
    }

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function ArrestJudgment(): ?string
    {
        $lastArrest = $this->Arrest()->first();

        return $lastArrest?->judgment;
    }

}
