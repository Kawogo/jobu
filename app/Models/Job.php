<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'location', 'description'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function requirements(): HasMany {
        return $this->hasMany(Requirement::class);
    }
}
