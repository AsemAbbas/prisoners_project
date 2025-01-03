<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Belong extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Arrest(): HasMany
    {
        return $this->hasMany(Arrest::class);
    }

    public function ArrestSuggestion(): HasMany
    {
        return $this->hasMany(ArrestSuggestion::class);
    }
}
