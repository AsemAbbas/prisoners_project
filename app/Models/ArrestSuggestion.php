<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArrestSuggestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getJudgmentAttribute(): ?string
    {
        $attributes = [];

        if (isset($this->judgment_in_lifetime) && $this->judgment_in_lifetime != '') {
            $attributes[] = $this->judgment_in_lifetime . ' مؤبد ';
        }

        if (isset($this->judgment_in_years) && $this->judgment_in_years != '') {
            $attributes[] = $this->judgment_in_years . ' سنوات ';
        }

        if (isset($this->judgment_in_months) && $this->judgment_in_months != '') {
            $attributes[] = $this->judgment_in_months . ' شهور ';
        }

        if (count($attributes) > 0) {
            return implode('', $attributes);
        } else {
            return null; // Or any other indicator that no attributes are set
        }
    }


    public function Prisoner(): BelongsTo
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function Health(): BelongsToMany
    {
        return $this->belongsToMany(Health::class, 'arrest_suggestions_healths', 'arrest_suggestion_id', 'health_id');
    }

    public function Belong(): BelongsTo
    {
        return $this->belongsTo(Belong::class);
    }

}