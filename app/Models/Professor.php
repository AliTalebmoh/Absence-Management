<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'first_name',
        'last_name'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->title} {$this->first_name} {$this->last_name}");
    }
} 