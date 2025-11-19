<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'license_number',
        'specialization',
        'experience_years',
        'qualifications',
        'consultation_fee',
        'available_days',
        'start_time',
        'end_time',
        'slot_duration',
        'is_available',
    ];

    protected $casts = [
        'available_days' => 'array',
        'consultation_fee' => 'decimal:2',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'user_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Helper methods
    public function getAvailableSlots($date)
    {
        $dayOfWeek = strtolower($date->format('l'));
        
        if (!in_array($dayOfWeek, $this->available_days)) {
            return [];
        }

        $slots = [];
        $currentTime = $this->start_time;
        
        while ($currentTime < $this->end_time) {
            $slots[] = $currentTime->format('H:i');
            $currentTime->addMinutes($this->slot_duration);
        }

        return $slots;
    }
}