<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Troupe extends Model
{
    protected $fillable = [
        'name',
        'description',
        'visibility',
        'created_by',
        'avatar_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::uuid();
        });
    }

    // Relationships

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->hasMany(Membership::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function interestTags()
    {
        return $this->belongsToMany(InterestTag::class, 'interest_tag_troupe');
    }
}
