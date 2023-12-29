<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function News(): HasMany
    {
        return $this->hasMany(News::class);
    }
}
