<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyIDNumberConfirm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'family_id_number_confirms';
    protected $guarded = [];

    public function PrisonerConfirm(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PrisonerConfirm::class);
    }
}
