<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'class_id'];

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}
