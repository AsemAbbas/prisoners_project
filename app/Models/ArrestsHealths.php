<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArrestsHealths extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Health(): BelongsTo
    {
        return $this->belongsTo(Health::class);
    }

    public function Arrest(): BelongsTo
    {
        return $this->belongsTo(Arrest::class);
    }
}
