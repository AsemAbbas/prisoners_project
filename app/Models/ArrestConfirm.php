<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArrestConfirm extends Model
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

    public function PrisonerConfirm(): BelongsTo
    {
        return $this->belongsTo(PrisonerConfirm::class);
    }

    public function Belong(): BelongsTo
    {
        return $this->belongsTo(Belong::class);
    }

}
