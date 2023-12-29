<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Health extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Arrest(): BelongsTo
    {
        return $this->belongsTo(Arrest::class);
    }

    public function ArrestSuggestion(): HasMany
    {
        return $this->hasMany(ArrestSuggestion::class);
    }

    public function ArrestsHealths(): HasMany
    {
        return $this->hasMany(ArrestsHealths::class);
    }
}
