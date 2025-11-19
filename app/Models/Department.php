<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'head_doctor_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function doctors()
    {
        return $this->hasMany(DoctorProfile::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function headDoctor()
    {
        return $this->belongsTo(User::class, 'head_doctor_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}