<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AiChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'chat_type',
        'conversation_history',
        'extracted_symptoms',
        'ai_recommendations',
        'appointment_created',
        'created_appointment_id',
        'status',
    ];

    protected $casts = [
        'conversation_history' => 'array',
        'extracted_symptoms' => 'array',
        'ai_recommendations' => 'array',
        'appointment_created' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($session) {
            if (empty($session->session_id)) {
                $session->session_id = 'chat_' . Str::random(16);
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdAppointment()
    {
        return $this->belongsTo(Appointment::class, 'created_appointment_id');
    }

    // Helper methods
    public function addMessage($role, $content, $metadata = [])
    {
        $history = $this->conversation_history ?? [];
        
        $history[] = [
            'role' => $role, // 'user' or 'assistant'
            'content' => $content,
            'timestamp' => now()->toISOString(),
            'metadata' => $metadata,
        ];
        
        $this->conversation_history = $history;
        $this->save();
    }

    public function getLastUserMessage()
    {
        $history = $this->conversation_history ?? [];
        
        for ($i = count($history) - 1; $i >= 0; $i--) {
            if ($history[$i]['role'] === 'user') {
                return $history[$i];
            }
        }
        
        return null;
    }

    public function extractSymptoms($symptoms)
    {
        $currentSymptoms = $this->extracted_symptoms ?? [];
        $this->extracted_symptoms = array_unique(array_merge($currentSymptoms, $symptoms));
        $this->save();
    }

    public function addRecommendation($type, $data)
    {
        $recommendations = $this->ai_recommendations ?? [];
        
        $recommendations[] = [
            'type' => $type, // 'doctor', 'medicine', 'first_aid', 'appointment'
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ];
        
        $this->ai_recommendations = $recommendations;
        $this->save();
    }
}