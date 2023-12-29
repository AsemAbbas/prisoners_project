<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Prisoner(): HasMany
    {
        return $this->hasMany(Prisoner::class);
    }
    public function PrisonerSuggestion(): HasMany
    {
        return $this->hasMany(PrisonerSuggestion::class);
    }
}
