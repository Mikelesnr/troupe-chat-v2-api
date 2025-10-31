<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InterestTag extends Model
{
    protected $fillable = [
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::uuid();
        });
    }

    // Relationships

    public function troupes()
    {
        return $this->belongsToMany(Troupe::class, 'interest_tag_troupe');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }
}
