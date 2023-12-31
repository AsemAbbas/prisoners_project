<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Town extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
