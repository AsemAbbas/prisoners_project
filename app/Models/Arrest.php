<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arrest extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getJudgmentAttribute(): ?string
    {
        $attributes = [];

        if ($this->judgment_in_lifetime !== null && $this->judgment_in_lifetime !== '') {
            $attributes[] = $this->judgment_in_lifetime . ' مؤبد ';
        }

        if ($this->judgment_in_years !== null && $this->judgment_in_years !== '') {
            $attributes[] = $this->judgment_in_years . ' سنوات ';
        }

        if ($this->judgment_in_months !== null && $this->judgment_in_months !== '') {
            $attributes[] = $this->judgment_in_months . ' شهور ';
        }

        if (count($attributes) > 0) {
            return implode('', $attributes);
        } else {
            return null;
        }
    }

    public function Prisoner(): BelongsTo
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function Belong(): BelongsTo
    {
        return $this->belongsTo(Belong::class);
    }
}
