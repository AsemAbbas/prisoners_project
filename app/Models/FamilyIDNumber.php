<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyIDNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'family_id_numbers';
    protected $guarded = [];

    public function Prisoner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Prisoner::class);
    }
}
