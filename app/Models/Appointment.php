<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'appointment_date',
        'status',
        'booking_type',
        'symptoms',
        'notes',
        'consultation_fee',
        'is_emergency',
        'booking_reference',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'consultation_fee' => 'decimal:2',
        'is_emergency' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($appointment) {
            if (empty($appointment->booking_reference)) {
                $appointment->booking_reference = 'APT-' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>', now());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helper methods
    public function isToday()
    {
        return $this->appointment_date->isToday();
    }

    public function isPast()
    {
        return $this->appointment_date->isPast();
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['scheduled', 'confirmed']) && 
               $this->appointment_date->isFuture();
    }
}