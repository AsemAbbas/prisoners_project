<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrisonersPrisonerTypes extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function PrisonerType(): BelongsTo
    {
        return $this->belongsTo(PrisonerType::class);
    }

    public function Prisoner(): BelongsTo
    {
        return $this->belongsTo(Prisoner::class);
    }
}
