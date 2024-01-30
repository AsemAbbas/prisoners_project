<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyIDNumberSuggestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'family_id_number_suggestions';
    protected $guarded = [];

    public function PrisonerSuggestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PrisonerSuggestion::class);
    }
}
