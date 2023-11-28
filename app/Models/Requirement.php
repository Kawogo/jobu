<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'job_id'];

    public function job(): BelongsTo {
        return $this->belongsTo(Job::class);
    }

    public function experience(): HasOne {
        return $this->hasOne(Experience::class);
    }
}
