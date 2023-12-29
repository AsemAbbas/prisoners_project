<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrisonerType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Prisoner(): BelongsTo
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function PrisonersPrisonerTypes(): HasMany
    {
        return $this->hasMany(PrisonersPrisonerTypes::class);
    }
}
