<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArrestSuggestionsHealths extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Health(): BelongsTo
    {
        return $this->belongsTo(Health::class);
    }

    public function ArrestSuggestion(): BelongsTo
    {
        return $this->belongsTo(ArrestSuggestion::class);
    }
}
