<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelativesPrisoner extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->second_name . ' ' . $this->third_name . ' ' . $this->last_name;
    }

    public function Relationship(): BelongsTo
    {
        return $this->belongsTo(Relationship::class);
    }


    public function Prisoner(): BelongsTo
    {
        return $this->belongsTo(Prisoner::class);
    }
}
