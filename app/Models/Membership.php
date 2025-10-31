<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'troupe_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::uuid();
        });
    }

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function troupe()
    {
        return $this->belongsTo(Troupe::class);
    }
}
