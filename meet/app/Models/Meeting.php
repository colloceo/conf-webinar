<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Meeting extends Model
{
    protected $fillable = [
        'title',
        'description', 
        'host_id',
        'is_active',
        'settings',
        'scheduled_at'
    ];

    protected $casts = [
        'settings' => 'array',
        'scheduled_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($meeting) {
            $meeting->slug = Str::uuid();
        });
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}