<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'content',
        'troupe_id',
        'conversation_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? Str::uuid();
        });
    }

    // Relationships

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function troupe()
    {
        return $this->belongsTo(Troupe::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
