<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserInterest extends Model
{
    protected $fillable = [
        'user_id',
        'interest_tag_id',
    ];

    public $timestamps = false; // Optional: if your pivot table doesn't use timestamps

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::uuid();
        });
    }

    // Relationships (optional, for clarity)

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function interestTag()
    {
        return $this->belongsTo(InterestTag::class);
    }
}
